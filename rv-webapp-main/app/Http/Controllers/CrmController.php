<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\EmployeeReport;
use App\Models\Project;
use App\Models\User;
use App\Support\CrmDemoData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CrmController extends Controller
{
    public function __construct(private readonly CrmDemoData $demo)
    {
    }

    public function login(Request $request): View|RedirectResponse
    {
        if ($this->currentUser($request)) {
            return redirect()->route('dashboard');
        }

        return view('auth.login', [
            'pageTitle' => 'LeadFlow CRM',
            'sampleUsers' => User::query()
                ->orderByRaw("CASE role WHEN 'Admin/Owner' THEN 0 WHEN 'Manager' THEN 1 ELSE 2 END")
                ->orderBy('name')
                ->get(['name', 'email', 'role']),
        ]);
    }

    public function signIn(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = trim((string) $request->string('username'));

        $user = User::query()
            ->where('email', $username)
            ->orWhereRaw('LOWER(name) = ?', [Str::lower($username)])
            ->first();

        if (! $user || ! Hash::check((string) $request->string('password'), $user->password)) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'username' => 'Invalid credentials. Use one of the seeded CRM users and password 12345.',
                ]);
        }

        $request->session()->regenerate();
        $request->session()->put('crm_user_id', $user->id);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('crm_user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard(Request $request): View
    {
        $user = $this->currentUserOrFail($request);

        return view('crm.dashboard', array_merge(
            $this->demo->shell($user, 'dashboard'),
            ['dashboard' => $this->demo->dashboard($user)],
        ));
    }

    public function assignLeads(Request $request): View
    {
        $user = $this->currentUserOrFail($request);

        return view('crm.assign-leads', array_merge(
            $this->demo->shell($user, 'assign-leads'),
            ['assignLeads' => $this->demo->assignLeads($user, $request->query())],
        ));
    }

    public function updateLeadAssignment(Request $request, Lead $lead): RedirectResponse
    {
        $user = $this->currentUserOrFail($request);

        abort_unless($this->canAccessLead($user, $lead), 403);

        if ($this->isAgent($user)) {
            $validated = $request->validate([
                'status' => ['required', 'string', 'max:64'],
            ]);

            $lead->forceFill([
                'status' => $validated['status'],
            ])->save();

            return back()->with('assignment_updated', $lead->lead_code.' status updated.');
        }

        if ($this->isManager($user)) {
            $assignableUserIds = $this->assignableUserIdsForManager($user);

            $validated = $request->validate([
                'assigned_to_user_id' => ['nullable', 'integer', Rule::in($assignableUserIds)],
                'status' => ['required', 'string', 'max:64'],
            ]);

            $lead->forceFill([
                'manager_user_id' => $user->id,
                'assigned_to_user_id' => $validated['assigned_to_user_id'] ?? null,
                'status' => $validated['status'],
            ])->save();

            return back()->with('assignment_updated', $lead->lead_code.' assignment updated.');
        }

        $validated = $request->validate([
            'manager_user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'Manager')),
            ],
            'assigned_to_user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', ['Manager', 'Agent'])),
            ],
            'status' => ['required', 'string', 'max:64'],
        ]);

        $lead->forceFill([
            'manager_user_id' => $validated['manager_user_id'] ?? null,
            'assigned_to_user_id' => $validated['assigned_to_user_id'] ?? null,
            'status' => $validated['status'],
        ])->save();

        return back()->with('assignment_updated', $lead->lead_code.' assignment updated.');
    }

    public function createLead(Request $request): View
    {
        $user = $this->currentUserOrFail($request);
        abort_unless($this->canAccessArea($user, 'create-lead'), 403);

        return view('crm.add-lead', array_merge(
            $this->demo->shell(
                user: $user,
                activeNav: 'assign-leads',
                sectionTitle: 'Add Lead',
                sectionSubtitle: 'Create a new lead with the same structured workspace used for lead details',
            ),
            ['addLead' => $this->demo->addLead()],
        ));
    }

    public function showLead(Request $request, string $leadId): View
    {
        $origin = (string) $request->query('origin', 'leads');
        $projectId = $request->query('project');
        $user = $this->currentUserOrFail($request);
        abort_unless($this->canAccessLeadCode($user, $leadId, is_string($projectId) ? $projectId : null), 403);

        $activeNav = match ($origin) {
            'projects', 'completed-projects' => 'projects',
            'follow-ups' => 'follow-ups',
            default => 'assign-leads',
        };

        return view('crm.lead-details', array_merge(
            $this->demo->shell(
                user: $user,
                activeNav: $activeNav,
                sectionTitle: 'Lead Details',
                sectionSubtitle: 'Review complete lead history and customer context',
            ),
            ['leadDetails' => $this->demo->leadDetail($user, $leadId, $origin, is_string($projectId) ? $projectId : null)],
        ));
    }

    public function projects(Request $request): View
    {
        $user = $this->currentUserOrFail($request);
        abort_unless($this->canAccessArea($user, 'projects'), 403);

        return view('crm.projects', array_merge(
            $this->demo->shell($user, 'projects'),
            ['projects' => $this->demo->projects($user, $request->query())],
        ));
    }

    public function followUps(Request $request): View
    {
        $user = $this->currentUserOrFail($request);

        return view('crm.follow-ups', array_merge(
            $this->demo->shell($user, 'follow-ups'),
            ['followUps' => $this->demo->followUps($user, $request->query())],
        ));
    }

    public function reports(Request $request): View
    {
        $user = $this->currentUserOrFail($request);

        return view('crm.reports', array_merge(
            $this->demo->shell($user, 'reports'),
            ['reports' => $this->demo->reports($user, $request->query())],
        ));
    }

    public function storeReport(Request $request): RedirectResponse
    {
        $user = $this->currentUserOrFail($request);
        abort_unless($this->isAgent($user), 403);

        $validated = $request->validate([
            'report_date' => ['required', 'date'],
            'work_started_at' => ['nullable', 'date_format:H:i'],
            'work_ended_at' => ['nullable', 'date_format:H:i'],
            'leads_contacted' => ['required', 'integer', 'min:0', 'max:999'],
            'follow_ups_completed' => ['required', 'integer', 'min:0', 'max:999'],
            'notes_added' => ['required', 'integer', 'min:0', 'max:999'],
            'quotations_shared' => ['required', 'integer', 'min:0', 'max:999'],
            'calls_made' => ['required', 'integer', 'min:0', 'max:999'],
            'whatsapp_messages' => ['required', 'integer', 'min:0', 'max:999'],
            'emails_sent' => ['required', 'integer', 'min:0', 'max:999'],
            'summary' => ['required', 'string', 'max:2000'],
            'issues' => ['nullable', 'string', 'max:2000'],
            'tomorrow_plan' => ['nullable', 'string', 'max:2000'],
        ]);

        $reportDate = \Illuminate\Support\Carbon::parse($validated['report_date'])->startOfDay();

        $report = EmployeeReport::query()
            ->where('user_id', $user->id)
            ->whereBetween('report_date', [
                $reportDate->toDateString(),
                $reportDate->copy()->endOfDay()->format('Y-m-d H:i:s'),
            ])
            ->first();

        $report ??= new EmployeeReport([
                'user_id' => $user->id,
                'report_date' => $reportDate->toDateString(),
        ]);

        $report->fill([
            ...$validated,
            'manager_id' => $user->manager_id,
            'status' => 'Submitted',
            'manager_feedback' => null,
        ])->save();

        return back()->with('report_saved', 'Daily report submitted to your manager and owner.');
    }

    public function reviewReport(Request $request, EmployeeReport $report): RedirectResponse
    {
        $user = $this->currentUserOrFail($request);
        abort_unless($this->canReviewReport($user, $report), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['Reviewed', 'Need clarification', 'Approved'])],
            'manager_feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $report->forceFill($validated)->save();

        return back()->with('report_reviewed', $report->employee?->name.' report updated.');
    }

    public function employees(Request $request): View
    {
        $user = $this->currentUserOrFail($request);
        abort_unless($this->canAccessArea($user, 'employees'), 403);

        return view('crm.employees', array_merge(
            $this->demo->shell($user, 'employees'),
            ['employees' => $this->demo->employees($user)],
        ));
    }

    public function settings(Request $request): View
    {
        $user = $this->currentUserOrFail($request);
        abort_unless($this->canAccessArea($user, 'settings'), 403);

        return view('crm.settings', array_merge(
            $this->demo->shell($user, 'settings'),
            ['settings' => $this->demo->settings()],
        ));
    }

    private function currentUser(Request $request): ?User
    {
        $userId = $request->session()->get('crm_user_id');

        if (! is_numeric($userId)) {
            return null;
        }

        return User::query()->find((int) $userId);
    }

    private function currentUserOrFail(Request $request): User
    {
        $user = $this->currentUser($request);

        abort_unless($user, 401);

        return $user;
    }

    private function canAccessArea(User $user, string $area): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        if ($this->isManager($user)) {
            return in_array($area, ['create-lead', 'projects', 'employees', 'reports'], true);
        }

        return false;
    }

    private function canAccessLeadCode(User $user, string $leadCode, ?string $projectCode = null): bool
    {
        if ($projectCode) {
            $project = Project::query()
                ->with(['lead.assignedTo'])
                ->where('project_code', $projectCode)
                ->first();

            return $project ? $this->canAccessProject($user, $project) : false;
        }

        $lead = Lead::query()
            ->with('assignedTo')
            ->where('lead_code', $leadCode)
            ->first();

        return $lead ? $this->canAccessLead($user, $lead) : false;
    }

    private function canAccessProject(User $user, Project $project): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        $project->loadMissing(['lead.assignedTo']);

        if ($this->isManager($user)) {
            return (int) $project->owner_id === (int) $user->id
                || ($project->lead && $this->canAccessLead($user, $project->lead));
        }

        return $project->lead && $this->canAccessLead($user, $project->lead);
    }

    private function canAccessLead(User $user, Lead $lead): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        $lead->loadMissing('assignedTo');

        if ($this->isManager($user)) {
            return (int) $lead->manager_user_id === (int) $user->id
                || (int) $lead->assigned_to_user_id === (int) $user->id
                || (int) $lead->assignedTo?->manager_id === (int) $user->id;
        }

        return (int) $lead->assigned_to_user_id === (int) $user->id;
    }

    private function canReviewReport(User $user, EmployeeReport $report): bool
    {
        if ($this->isOwner($user)) {
            return true;
        }

        if (! $this->isManager($user)) {
            return false;
        }

        $report->loadMissing('employee');

        return (int) $report->manager_id === (int) $user->id
            || (int) $report->employee?->manager_id === (int) $user->id;
    }

    private function assignableUserIdsForManager(User $manager): array
    {
        return User::query()
            ->where('id', $manager->id)
            ->orWhere('manager_id', $manager->id)
            ->pluck('id')
            ->all();
    }

    private function isOwner(User $user): bool
    {
        return $user->role === 'Admin/Owner';
    }

    private function isManager(User $user): bool
    {
        return $user->role === 'Manager';
    }

    private function isAgent(User $user): bool
    {
        return $user->role === 'Agent';
    }
}
