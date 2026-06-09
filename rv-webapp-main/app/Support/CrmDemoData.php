<?php

namespace App\Support;

use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\EmployeeReport;
use App\Models\LeadNote;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CrmDemoData
{
    public function shell(User $user, string $activeNav, ?string $sectionTitle = null, ?string $sectionSubtitle = null): array
    {
        $defaults = [
            'dashboard' => ['title' => 'Dashboard', 'subtitle' => 'Overview of your business'],
            'assign-leads' => ['title' => 'Assign Leads', 'subtitle' => 'Distribute incoming opportunities across the team'],
            'projects' => ['title' => 'Projects', 'subtitle' => 'Track project execution, pipeline stage, and ownership'],
            'follow-ups' => ['title' => 'Follow Ups', 'subtitle' => 'Keep upcoming reminders and customer actions visible'],
            'reports' => ['title' => 'Reports', 'subtitle' => 'Submit, review, and approve daily employee work reports'],
            'employees' => ['title' => 'Employees & Reports', 'subtitle' => 'Review staff workload, ownership, and reporting'],
            'settings' => ['title' => 'Settings', 'subtitle' => 'Manage workspace preferences and system options'],
        ][$activeNav];

        return [
            'appTitle' => 'LeadFlow CRM',
            'workspaceVersion' => 'LeadFlow CRM v1.0',
            'workspaceSubtitle' => 'MySQL-backed Laravel workspace',
            'headerRangeLabel' => $user->role.' Workspace',
            'headerRangeValue' => $this->headerRangeValue($user),
            'currentUserName' => $user->name,
            'currentUserRole' => $user->role,
            'currentUserInitials' => $this->initials($user->name),
            'activeNav' => $activeNav,
            'sectionTitle' => $sectionTitle ?? $defaults['title'],
            'sectionSubtitle' => $sectionSubtitle ?? $defaults['subtitle'],
            'navigation' => $this->navigation($user),
        ];
    }

    public function navigation(User $user): array
    {
        $items = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'abbr' => 'DB', 'route' => 'dashboard', 'divider_before' => false],
            ['key' => 'assign-leads', 'label' => 'Assign Leads', 'abbr' => 'AL', 'route' => 'assign-leads.index', 'divider_before' => false],
            ['key' => 'projects', 'label' => 'Projects', 'abbr' => 'PR', 'route' => 'projects.index', 'divider_before' => false],
            ['key' => 'follow-ups', 'label' => 'Follow Ups', 'abbr' => 'FU', 'route' => 'follow-ups.index', 'divider_before' => false],
            ['key' => 'reports', 'label' => 'Reports', 'abbr' => 'RP', 'route' => 'reports.index', 'divider_before' => false],
            ['key' => 'employees', 'label' => 'Employees & Reports', 'abbr' => 'EM', 'route' => 'employees.index', 'divider_before' => true],
            ['key' => 'settings', 'label' => 'Settings', 'abbr' => 'ST', 'route' => 'settings.index', 'divider_before' => false],
        ];

        if ($this->isManager($user)) {
            return collect($items)
                ->reject(fn (array $item): bool => $item['key'] === 'settings')
                ->values()
                ->all();
        }

        if ($this->isAgent($user)) {
            return [
                ['key' => 'dashboard', 'label' => 'My Dashboard', 'abbr' => 'DB', 'route' => 'dashboard', 'divider_before' => false],
                ['key' => 'assign-leads', 'label' => 'My Leads', 'abbr' => 'ML', 'route' => 'assign-leads.index', 'divider_before' => false],
                ['key' => 'follow-ups', 'label' => 'My Follow Ups', 'abbr' => 'FU', 'route' => 'follow-ups.index', 'divider_before' => false],
                ['key' => 'reports', 'label' => 'Submit Report', 'abbr' => 'SR', 'route' => 'reports.index', 'divider_before' => false],
            ];
        }

        return $items;
    }

    public function dashboard(User $user): array
    {
        $now = now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();
        $previousMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $previousMonthEnd = $previousMonthStart->copy()->endOfMonth();

        $totalLeads = $this->visibleLeadQuery($user)->count();
        $thisMonthLeads = $this->visibleLeadQuery($user)
            ->whereBetween('inquiry_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        $previousMonthLeads = $this->visibleLeadQuery($user)
            ->whereBetween('inquiry_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        $pendingFollowUps = $this->visibleFollowUpQuery($user)
            ->where('status', '!=', 'Completed')
            ->count();
        $dueBeforeNoon = $this->visibleFollowUpQuery($user)
            ->whereDate('due_at', $now->toDateString())
            ->whereTime('due_at', '<', '12:00:00')
            ->where('status', '!=', 'Completed')
            ->count();

        $poReceived = $this->visibleProjectQuery($user)
            ->where('po_status', 'PO Received')
            ->count();
        $poReceivedLastMonth = $this->visibleProjectQuery($user)
            ->where('po_status', 'PO Received')
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        $revenue = (float) $this->visibleProjectQuery($user)->sum('value_amount');
        $previousRevenue = (float) $this->visibleProjectQuery($user)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->sum('value_amount');

        $leadSources = $this->leadSources($user);
        $trend = $this->leadTrend($user);
        $topEmployees = $this->visibleTeamQuery($user)
            ->whereIn('role', ['Manager', 'Agent'])
            ->withCount(['assignedLeads' => fn (Builder $query) => $this->applyLeadVisibility($query, $user)])
            ->orderByDesc('assigned_leads_count')
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->values()
            ->map(function (User $user, int $index): array {
                $tones = ['amber', 'rose', 'gold', 'amber', 'orange'];

                return [
                    'name' => $user->name,
                    'leads' => $user->assigned_leads_count,
                    'initials' => $this->initials($user->name),
                    'tone' => $tones[$index] ?? 'amber',
                ];
            })
            ->all();

        return [
            'kicker' => $this->dashboardKicker($user),
            'title' => $this->dashboardTitle($user),
            'subtitle' => $this->dashboardSubtitle($user),
            'date_range_label' => 'Date Range',
            'date_range_text' => $this->dateRangeText($user),
            'metrics' => [
                [
                    'icon' => 'TL',
                    'theme' => 'theme-blue',
                    'label' => 'Total Leads',
                    'value' => number_format($totalLeads),
                    'trend' => $this->trendText($thisMonthLeads, $previousMonthLeads),
                    'comparison' => $thisMonthLeads.' enquiries added this month',
                ],
                [
                    'icon' => 'NL',
                    'theme' => 'theme-green',
                    'label' => 'New Leads',
                    'value' => number_format($thisMonthLeads),
                    'trend' => $this->trendText($thisMonthLeads, $previousMonthLeads),
                    'comparison' => $previousMonthLeads.' arrived last month',
                ],
                [
                    'icon' => 'FU',
                    'theme' => 'theme-rose',
                    'label' => 'Follow Ups',
                    'value' => number_format($pendingFollowUps),
                    'trend' => 'Pending customer actions',
                    'comparison' => $dueBeforeNoon.' due before noon today',
                ],
                [
                    'icon' => 'PO',
                    'theme' => 'theme-mint',
                    'label' => 'PO Received',
                    'value' => number_format($poReceived),
                    'trend' => $this->trendText($poReceived, $poReceivedLastMonth),
                    'comparison' => $this->visibleProjectQuery($user)->where('project_status', 'Completed')->count().' closed projects',
                ],
                [
                    'icon' => 'RV',
                    'theme' => 'theme-violet',
                    'label' => 'Revenue',
                    'value' => $this->money($revenue),
                    'trend' => $this->moneyTrend($revenue, $previousRevenue),
                    'comparison' => $this->visibleProjectQuery($user)->count().' tracked projects in pipeline',
                ],
            ],
            'sources' => $leadSources,
            'trend' => $trend,
            'employees' => $topEmployees,
        ];
    }

    public function assignLeads(User $user, array $filters = []): array
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $source = (string) ($filters['source'] ?? 'All Sources');
        $status = (string) ($filters['status'] ?? 'All Statuses');
        $manager = (string) ($filters['manager'] ?? 'All Managers');
        $owner = (string) ($filters['owner'] ?? 'All Owners');

        $query = $this->visibleLeadQuery($user)->with(['manager', 'assignedTo']);

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('lead_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('customer_mobile', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('external_source_id', 'like', "%{$search}%")
                    ->orWhereHas('manager', fn (Builder $subQuery) => $subQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('assignedTo', fn (Builder $subQuery) => $subQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($source !== 'All Sources') {
            $query->where('source', $source);
        }

        if ($status !== 'All Statuses') {
            $query->where('status', $status);
        }

        if ($manager !== 'All Managers') {
            $query->whereHas('manager', fn (Builder $subQuery) => $subQuery->where('name', $manager));
        }

        if ($owner !== 'All Owners') {
            $query->whereHas('assignedTo', fn (Builder $subQuery) => $subQuery->where('name', $owner));
        }

        $rows = $query
            ->orderByDesc('inquiry_at')
            ->get()
            ->map(fn (Lead $lead): array => [
                'id' => $lead->id,
                'lead_id' => $lead->lead_code,
                'customer_name' => $lead->customer_name,
                'company' => $lead->company_name ?? 'No company',
                'mobile' => $lead->customer_mobile ?? 'Not available',
                'source' => $lead->source ?? 'Other',
                'platform_ref' => $lead->external_source_id ?? 'Manual lead',
                'status' => $lead->status,
                'manager' => $lead->manager?->name ?? 'Needs manager',
                'manager_user_id' => $lead->manager_user_id,
                'assigned_to' => $lead->assignedTo?->name ?? 'Unassigned',
                'assigned_to_user_id' => $lead->assigned_to_user_id,
                'assignment_rule' => $this->assignmentRule($lead),
                'created_date' => $this->formatDate($lead->inquiry_at),
                'status_tone' => $this->statusTone($lead->status),
                'source_tone' => $this->sourceTone($lead->source),
            ])
            ->values()
            ->all();

        return [
            'rows' => $rows,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'source' => $source,
                'status' => $status,
                'manager' => $manager,
                'owner' => $owner,
            ],
            'source_options' => array_merge(['All Sources'], $this->distinctVisibleLeadValues($user, 'source')),
            'status_options' => array_merge(['All Statuses'], $this->leadStatusOptions()),
            'manager_options' => array_merge(['All Managers'], $this->distinctManagerNames($user)),
            'owner_options' => array_merge(['All Owners'], $this->distinctOwnerNames($user)),
            'manager_users' => $this->managerOptions($user),
            'employee_users' => $this->employeeOptions($user),
            'integration_cards' => $this->integrationCards($user),
            'assignment_flow' => $this->assignmentFlow($user),
            'routing_rules' => $this->routingRules(),
            'can_view_intake' => ! $this->isAgent($user),
            'can_view_routing_rules' => ! $this->isAgent($user),
            'can_add_lead' => ! $this->isAgent($user),
            'can_filter_manager' => ! $this->isAgent($user),
            'can_filter_owner' => ! $this->isAgent($user),
            'can_assign_manager' => $this->isOwner($user),
            'can_assign_employee' => ! $this->isAgent($user),
            'can_update_status' => true,
            'assignment_title' => $this->isAgent($user) ? 'My Leads' : 'Leads List',
            'assignment_subtitle' => $this->assignmentSubtitle($user),
            'count_summary' => count($rows).' leads visible',
        ];
    }

    public function addLead(): array
    {
        return [
            'hero_title' => 'Add New Lead',
            'hero_subtitle' => 'Create a structured lead profile before assignment or project conversion.',
            'lead_avatar_text' => 'NL',
            'lead_source_label' => 'Manual Entry',
            'lead_status_label' => 'Draft',
            'draft' => [
                'customer_name' => '',
                'mobile' => '',
                'email' => '',
                'company' => '',
                'city' => '',
                'state' => '',
                'country' => '',
                'requirement_message' => 'Customer asked for a compact servo-based pouch line with commissioning support and onsite operator training.',
                'product' => '',
                'quantity' => '',
                'budget' => '',
                'delivery' => '',
                'category' => '',
                'lead_id' => 'Will be generated on save',
                'assigned_to' => 'Select owner',
                'source' => 'IndiaMART',
                'status' => 'New',
                'inquiry_date' => now()->format('d M Y'),
                'created_on' => now()->format('d M Y'),
            ],
            'notes' => [
                ['text' => 'Need to confirm final pouch dimensions before quoting.', 'author' => 'Owner Admin', 'timestamp' => now()->format('d M Y, h:i A')],
                ['text' => 'Customer prefers a WhatsApp-first update after price approval.', 'author' => 'Owner Admin', 'timestamp' => now()->subMinutes(20)->format('d M Y, h:i A')],
            ],
            'documents' => [
                ['file_type' => 'PDF', 'file_name' => 'buyer-requirement-note.pdf', 'uploaded_on' => 'Ready after save'],
                ['file_type' => 'XLS', 'file_name' => 'draft-costing-template.xlsx', 'uploaded_on' => 'Ready after save'],
            ],
            'follow_up' => [
                'subject' => 'Follow up',
                'date' => now()->addDay()->format('d M Y'),
                'time' => '11:00 AM',
                'company_name' => 'Company name will use the saved lead',
            ],
            'import_status_text' => 'This page is now reading owners and downstream workflows from the MySQL-backed CRM. Save/create wiring is the next step after the UI migration.',
        ];
    }

    public function leadDetail(User $user, string $leadCode, string $origin = 'leads', ?string $projectCode = null): array
    {
        if (in_array($origin, ['projects', 'completed-projects'], true)) {
            return $this->projectLeadDetail($user, $projectCode, $origin);
        }

        $lead = Lead::query()
            ->with([
                'assignedTo',
                'followUps.assignedTo',
                'notes.author',
                'documents.uploadedBy',
            ])
            ->where('lead_code', $leadCode)
            ->firstOrFail();

        return $this->buildLeadDetail(
            user: $user,
            lead: $lead,
            origin: $origin,
            breadcrumbText: $origin === 'follow-ups' ? 'Follow Ups > Lead Details' : 'Leads > Lead Details',
            backText: $origin === 'follow-ups' ? 'Back to Follow Ups' : 'Back to Leads',
            backRoute: $origin === 'follow-ups' ? route('follow-ups.index') : route('assign-leads.index'),
        );
    }

    public function projects(User $user, array $filters = []): array
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $status = (string) ($filters['status'] ?? 'All Statuses');
        $owner = (string) ($filters['owner'] ?? 'All Owners');
        $tab = (string) ($filters['tab'] ?? 'active');

        $rowsQuery = $this->visibleProjectQuery($user)->with(['lead.assignedTo', 'owner']);

        if ($tab === 'completed') {
            $rowsQuery->where('project_status', 'Completed');
        } else {
            $rowsQuery->where('project_status', '!=', 'Completed');
        }

        if ($search !== '') {
            $rowsQuery->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('project_code', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('lead', function (Builder $subQuery) use ($search): void {
                        $subQuery
                            ->where('lead_code', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('company_name', 'like', "%{$search}%")
                            ->orWhere('customer_mobile', 'like', "%{$search}%")
                            ->orWhere('customer_email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status !== 'All Statuses') {
            $rowsQuery->where(function (Builder $builder) use ($status): void {
                $builder
                    ->where('project_status', $status)
                    ->orWhere('po_status', $status)
                    ->orWhereHas('lead', fn (Builder $subQuery) => $subQuery->where('status', $status));
            });
        }

        if ($owner !== 'All Owners') {
            $rowsQuery->whereHas('owner', fn (Builder $subQuery) => $subQuery->where('name', $owner));
        }

        $rowsCollection = $rowsQuery
            ->orderByRaw("CASE WHEN project_status = 'Completed' THEN 1 ELSE 0 END")
            ->orderBy('delivery_date')
            ->get();

        $rows = $rowsCollection->map(fn (Project $project): array => $this->mapProject($project))->values();
        $selectedCode = (string) ($filters['selected'] ?? ($rows->first()['project_id'] ?? ''));
        $selectedProject = $rows->firstWhere('project_id', $selectedCode) ?? $rows->first();

        $completedCollection = $this->visibleProjectQuery($user)
            ->with(['lead', 'owner'])
            ->where('project_status', 'Completed')
            ->orderByDesc('delivery_date')
            ->get();

        return [
            'tab' => $tab,
            'rows' => $rows->all(),
            'selected_project' => $selectedProject,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'status' => $status,
                'owner' => $owner,
            ],
            'status_options' => array_merge(['All Statuses'], $this->projectStatusOptions()),
            'owner_options' => array_merge(['All Owners'], $this->projectOwnerNames($user)),
            'active_count_summary' => $this->visibleProjectQuery($user)->where('project_status', '!=', 'Completed')->count().' active projects',
            'completed_count_summary' => $this->visibleProjectQuery($user)->where('project_status', 'Completed')->count().' completed projects',
            'page_summary' => 'Showing '.$rows->count().' project records',
            'completed_cards' => $completedCollection->map(fn (Project $project): array => $this->mapProject($project))->all(),
            'can_add_project' => ! $this->isAgent($user),
        ];
    }

    public function followUps(User $user, array $filters = []): array
    {
        $search = trim((string) ($filters['search'] ?? ''));

        $query = $this->visibleFollowUpQuery($user)->with(['lead', 'project', 'assignedTo']);

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('subject', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('lead', fn (Builder $subQuery) => $subQuery->where('customer_name', 'like', "%{$search}%")->orWhere('company_name', 'like', "%{$search}%"))
                    ->orWhereHas('assignedTo', fn (Builder $subQuery) => $subQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $rows = $query
            ->orderByRaw("CASE WHEN status = 'Completed' THEN 1 ELSE 0 END")
            ->orderBy('due_at')
            ->get()
            ->map(function (FollowUp $followUp): array {
                return [
                    'lead_id' => $followUp->lead?->lead_code ?? 'NA',
                    'project_id' => $followUp->project?->project_code,
                    'lead_name' => $followUp->lead?->customer_name ?? $followUp->project?->customer_name ?? 'Unknown lead',
                    'subject' => $followUp->subject,
                    'follow_up_date' => $this->formatDate($followUp->due_at),
                    'due_on' => $this->formatDateTime($followUp->due_at),
                    'time' => $this->formatTime($followUp->due_at),
                    'type' => $followUp->type,
                    'note' => $followUp->notes ?? 'No note added',
                    'assigned_to' => $followUp->assignedTo?->name ?? 'Unassigned',
                    'owner' => $followUp->assignedTo?->name ?? 'Unassigned',
                    'status' => $followUp->status,
                    'status_tone' => $this->statusTone($followUp->status),
                ];
            })
            ->values()
            ->all();

        return [
            'subtitle' => $this->isAgent($user)
                ? 'Your assigned reminders and customer actions are listed closest due first.'
                : 'Closest reminder first. Keep pending customer actions visible before they slip through the pipeline.',
            'filters' => ['search' => $filters['search'] ?? ''],
            'rows' => $rows,
            'count_summary' => count($rows).' follow ups visible',
            'can_add_follow_up' => ! $this->isAgent($user),
        ];
    }

    public function reports(User $user, array $filters = []): array
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $status = (string) ($filters['status'] ?? 'All Statuses');
        $today = now()->toDateString();
        $todayReport = EmployeeReport::query()
            ->where('user_id', $user->id)
            ->whereDate('report_date', $today)
            ->first();

        $query = $this->visibleReportQuery($user)->with(['employee.manager', 'manager']);

        if ($status !== 'All Statuses') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('summary', 'like', "%{$search}%")
                    ->orWhere('issues', 'like', "%{$search}%")
                    ->orWhere('tomorrow_plan', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn (Builder $subQuery) => $subQuery->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('manager', fn (Builder $subQuery) => $subQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $rows = $query
            ->orderByDesc('report_date')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (EmployeeReport $report): array => $this->mapReport($report))
            ->values()
            ->all();

        $assignedLeads = $this->visibleLeadQuery($user)->count();
        $pendingFollowUps = $this->visibleFollowUpQuery($user)->where('status', '!=', 'Completed')->count();
        $completedToday = $this->visibleFollowUpQuery($user)
            ->where('status', 'Completed')
            ->whereDate('completed_at', $today)
            ->count();
        $notesToday = LeadNote::query()
            ->where('author_user_id', $user->id)
            ->whereDate('created_at', $today)
            ->count();

        $reportCount = $this->visibleReportQuery($user)->count();
        $waitingReview = $this->visibleReportQuery($user)->where('status', 'Submitted')->count();
        $approvedCount = $this->visibleReportQuery($user)->where('status', 'Approved')->count();

        return [
            'title' => $this->isAgent($user) ? 'Submit Daily Report' : 'Team Daily Reports',
            'subtitle' => $this->isAgent($user)
                ? 'Share your working-hour activity with your manager and owner.'
                : 'Review employee reports, give feedback, and approve daily output.',
            'is_agent' => $this->isAgent($user),
            'can_review' => ! $this->isAgent($user),
            'manager_name' => $user->manager?->name ?? 'Owner Admin',
            'filters' => [
                'search' => $filters['search'] ?? '',
                'status' => $status,
            ],
            'status_options' => ['All Statuses', 'Submitted', 'Reviewed', 'Need clarification', 'Approved'],
            'review_status_options' => ['Reviewed', 'Need clarification', 'Approved'],
            'summary_cards' => [
                [
                    'label' => $this->isAgent($user) ? 'Assigned Leads' : 'Reports Submitted',
                    'value' => number_format($this->isAgent($user) ? $assignedLeads : $reportCount),
                    'detail' => $this->isAgent($user) ? 'Visible in My Leads' : 'Visible in your workspace',
                ],
                [
                    'label' => $this->isAgent($user) ? 'Pending Follow Ups' : 'Waiting Review',
                    'value' => number_format($this->isAgent($user) ? $pendingFollowUps : $waitingReview),
                    'detail' => $this->isAgent($user) ? 'Open customer actions' : 'Submitted by employees',
                ],
                [
                    'label' => $this->isAgent($user) ? 'Reports Sent' : 'Approved Reports',
                    'value' => number_format($this->isAgent($user) ? $reportCount : $approvedCount),
                    'detail' => $this->isAgent($user) ? 'Your total report history' : 'Closed by manager/owner',
                ],
            ],
            'auto_metrics' => [
                'assigned_leads' => $assignedLeads,
                'pending_follow_ups' => $pendingFollowUps,
                'completed_follow_ups_today' => $completedToday,
                'notes_added_today' => $notesToday,
            ],
            'form' => [
                'report_date' => $this->formatDateInput($todayReport?->report_date ?? $today),
                'work_started_at' => $this->formatTimeInput($todayReport?->work_started_at, '09:30'),
                'work_ended_at' => $this->formatTimeInput($todayReport?->work_ended_at, '18:30'),
                'leads_contacted' => $todayReport?->leads_contacted ?? 0,
                'follow_ups_completed' => $todayReport?->follow_ups_completed ?? $completedToday,
                'notes_added' => $todayReport?->notes_added ?? $notesToday,
                'quotations_shared' => $todayReport?->quotations_shared ?? 0,
                'calls_made' => $todayReport?->calls_made ?? 0,
                'whatsapp_messages' => $todayReport?->whatsapp_messages ?? 0,
                'emails_sent' => $todayReport?->emails_sent ?? 0,
                'summary' => $todayReport?->summary ?? '',
                'issues' => $todayReport?->issues ?? '',
                'tomorrow_plan' => $todayReport?->tomorrow_plan ?? '',
                'status' => $todayReport?->status ?? 'Not submitted',
                'manager_feedback' => $todayReport?->manager_feedback,
            ],
            'rows' => $rows,
            'recent_reports' => $this->visibleReportQuery($user)
                ->with(['employee.manager', 'manager'])
                ->where('user_id', $user->id)
                ->orderByDesc('report_date')
                ->limit(5)
                ->get()
                ->map(fn (EmployeeReport $report): array => $this->mapReport($report))
                ->values()
                ->all(),
            'count_summary' => count($rows).' reports visible',
        ];
    }

    public function employees(User $user): array
    {
        $rows = $this->visibleTeamQuery($user)
            ->orderByRaw("CASE role WHEN 'Admin/Owner' THEN 0 WHEN 'Manager' THEN 1 ELSE 2 END")
            ->orderBy('name')
            ->get()
            ->map(fn (User $user): array => [
                'employee_id' => 'EMP'.str_pad((string) $user->id, 3, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'Not available',
                'role' => $user->role,
                'manager' => $user->manager?->name ?? 'Not assigned',
                'lead_count' => $user->assignedLeads()->count(),
                'status' => $user->employment_status,
                'joined_date' => optional($user->joined_at)->format('d M Y') ?? 'Not set',
                'status_tone' => Str::lower($user->employment_status) === 'active' ? 'success' : 'rose',
            ])
            ->values()
            ->all();

        return [
            'title' => 'Employees',
            'body' => $this->isOwner($user)
                ? 'Owner view includes all managers, agents, status, and reporting.'
                : 'Manager view includes your team and their current lead load.',
            'rows' => $rows,
            'can_add_employee' => $this->isOwner($user),
        ];
    }

    public function settings(): array
    {
        return [
            'title' => 'Settings',
            'body' => 'System preferences, team configuration, and integration keys will be managed here.',
            'cards' => [
                [
                    'title' => 'Security and Access',
                    'body' => 'User permissions now begin with the seeded role levels: Admin/Owner, Manager, and Agent.',
                ],
                [
                    'title' => 'Configuration Board',
                    'body' => 'Environment-specific settings, notification preferences, and office-level defaults will be laid out here.',
                ],
                [
                    'title' => 'Integration Readiness',
                    'body' => 'API keys, importer rules, and notification channels will connect here as we continue moving CRUD flows from the Avalonia app.',
                ],
            ],
        ];
    }

    private function projectLeadDetail(User $user, ?string $projectCode, string $origin): array
    {
        $project = Project::query()
            ->with([
                'lead.assignedTo',
                'lead.notes.author',
                'lead.documents.uploadedBy',
                'followUps.assignedTo',
                'owner',
            ])
            ->where('project_code', $projectCode)
            ->firstOrFail();

        return $this->buildLeadDetail(
            user: $user,
            lead: $project->lead,
            origin: $origin,
            breadcrumbText: $origin === 'completed-projects' ? 'Completed Projects > Project Details' : 'Projects > Project Details',
            backText: $origin === 'completed-projects' ? 'Back to Completed Projects' : 'Back to Projects',
            backRoute: route('projects.index', $origin === 'completed-projects' ? ['tab' => 'completed'] : []),
            sourceLabel: $project->po_status,
            sourceTone: $this->statusTone($project->po_status),
            statusLabel: $project->project_status,
            statusTone: $this->statusTone($project->project_status),
            title: $project->project_name,
            subtitle: $project->customer_name,
            followUps: $project->followUps,
        );
    }

    private function buildLeadDetail(
        User $user,
        Lead $lead,
        string $origin,
        string $breadcrumbText,
        string $backText,
        string $backRoute,
        ?string $sourceLabel = null,
        ?string $sourceTone = null,
        ?string $statusLabel = null,
        ?string $statusTone = null,
        ?string $title = null,
        ?string $subtitle = null,
        ?Collection $followUps = null,
    ): array {
        $lead->loadMissing(['manager', 'assignedTo', 'followUps.assignedTo', 'notes.author', 'documents.uploadedBy']);

        $followUps = ($followUps ?? $lead->followUps)
            ->sortBy('due_at')
            ->values();

        $notes = $lead->notes
            ->sortByDesc('created_at')
            ->values()
            ->map(fn ($note): array => [
                'note_text' => $note->note_text,
                'author' => $note->author?->name ?? 'Unknown author',
                'timestamp' => $this->formatDateTime($note->created_at),
            ])
            ->all();

        $documents = $lead->documents
            ->sortByDesc('uploaded_at')
            ->values()
            ->map(fn ($document): array => [
                'file_type' => $document->file_type ?? strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)),
                'file_name' => $document->file_name,
                'uploaded_on' => $this->formatDateTime($document->uploaded_at ?? $document->created_at),
            ])
            ->all();

        $timeline = collect([
            [
                'sort_at' => $lead->inquiry_at,
                'icon_text' => 'LD',
                'title' => 'Lead captured from '.($lead->source ?? 'manual entry'),
                'timestamp' => $this->formatDateTime($lead->inquiry_at),
                'description' => 'Lead was assigned to '.($lead->assignedTo?->name ?? 'the workspace').'.',
            ],
        ])
            ->concat($lead->notes->map(fn ($note) => [
                'sort_at' => $note->created_at,
                'icon_text' => 'NT',
                'title' => 'Internal note added',
                'timestamp' => $this->formatDateTime($note->created_at),
                'description' => $note->note_text,
            ]))
            ->concat($lead->documents->map(fn ($document) => [
                'sort_at' => $document->uploaded_at ?? $document->created_at,
                'icon_text' => 'DC',
                'title' => 'Document uploaded',
                'timestamp' => $this->formatDateTime($document->uploaded_at ?? $document->created_at),
                'description' => $document->file_name.' was attached to this lead.',
            ]))
            ->concat($followUps->map(fn ($followUp) => [
                'sort_at' => $followUp->due_at,
                'icon_text' => 'FU',
                'title' => 'Follow-up scheduled',
                'timestamp' => $this->formatDateTime($followUp->due_at),
                'description' => $followUp->subject.' for '.$this->formatDateTime($followUp->due_at).'.',
            ]))
            ->sortByDesc('sort_at')
            ->take(8)
            ->values()
            ->map(fn (array $item): array => Arr::except($item, 'sort_at'))
            ->all();

        return [
            'origin' => $origin,
            'breadcrumb_text' => $breadcrumbText,
            'back_text' => $backText,
            'back_route' => $backRoute,
            'can_edit_lead' => ! $this->isAgent($user),
            'can_assign_project' => ! $this->isAgent($user),
            'can_add_follow_up' => ! $this->isAgent($user),
            'can_upload_document' => ! $this->isAgent($user),
            'current_lead' => [
                'lead_name' => $title ?? $lead->customer_name,
                'lead_subtitle' => $subtitle ?? ($lead->company_name ?: 'No company'),
                'lead_avatar_text' => $this->initials($title ?? $lead->customer_name),
                'lead_source' => $sourceLabel ?? ($lead->source ?? 'Other'),
                'lead_source_tone' => $sourceTone ?? $this->sourceTone($lead->source),
                'lead_status' => $statusLabel ?? $lead->status,
                'lead_status_tone' => $statusTone ?? $this->statusTone($lead->status),
                'customer_mobile' => $lead->customer_mobile ?? 'Not available',
                'customer_email' => $lead->customer_email ?? 'Not available',
                'customer_company' => $lead->company_name ?? 'No company',
                'customer_city' => $lead->city ?? 'Not available',
                'customer_state' => $lead->state ?? 'Not available',
                'customer_country' => $lead->country ?? 'Not available',
                'requirement_message' => $lead->requirement_message ?? 'No requirement message recorded.',
                'requirement_product' => $lead->requirement_product ?? 'Not specified',
                'requirement_quantity' => $lead->requirement_quantity ?? 'Not specified',
                'requirement_budget' => $lead->requirement_budget ?? 'Not specified',
                'requirement_delivery' => $lead->requirement_delivery ?? 'Not specified',
                'requirement_category' => $lead->requirement_category ?? 'Not specified',
                'lead_id' => $lead->lead_code,
                'lead_manager' => $lead->manager?->name ?? 'Not assigned',
                'lead_assigned_to' => $lead->assignedTo?->name ?? 'Unassigned',
                'lead_created_on' => $this->formatDate($lead->created_at),
                'lead_inquiry_date' => $this->formatDateTime($lead->inquiry_at),
            ],
            'follow_ups' => $followUps->map(fn (FollowUp $followUp): array => [
                'subject' => $followUp->subject,
                'due_on' => $this->formatDateTime($followUp->due_at),
                'owner' => $followUp->assignedTo?->name ?? 'Unassigned',
                'status' => $followUp->status,
                'status_tone' => $this->statusTone($followUp->status),
                'note' => $followUp->notes ?? 'No note added',
            ])->all(),
            'notes' => $notes,
            'documents' => $documents,
            'timeline' => $timeline,
        ];
    }

    private function mapProject(Project $project): array
    {
        $lead = $project->lead;

        return [
            'project_id' => $project->project_code,
            'project_name' => $project->project_name,
            'customer' => $project->customer_name,
            'company_name' => $lead?->company_name ?? $project->customer_name,
            'inquiry_id' => $lead?->lead_code ?? 'NA',
            'inquiry_date_time' => $this->formatDateTime($lead?->inquiry_at),
            'customer_name' => $lead?->customer_name ?? $project->customer_name,
            'mobile_number' => $lead?->customer_mobile ?? 'Not available',
            'email_address' => $lead?->customer_email ?? 'Not available',
            'city_state_country' => collect([$lead?->city, $lead?->state, $lead?->country])->filter()->implode(' / '),
            'product_name' => $lead?->requirement_product ?? 'Not specified',
            'requirement_message' => $lead?->requirement_message ?? 'No requirement captured.',
            'product_category' => $lead?->requirement_category ?? 'Not specified',
            'lead_source' => $lead?->source ?? $project->source ?? 'Other',
            'inquiry_status' => $lead?->status ?? 'Unknown',
            'buyer_type' => $lead?->buyer_type ?? 'Not specified',
            'gst_or_website' => $lead?->gst_or_website ?? 'Not specified',
            'po_status' => $project->po_status,
            'project_status' => $project->project_status,
            'value' => $this->money((float) $project->value_amount),
            'delivery_date' => optional($project->delivery_date)->format('d M Y') ?? 'Not scheduled',
            'owner' => $project->owner?->name ?? 'Unassigned',
            'tab' => $project->project_status === 'Completed' ? 'completed' : 'active',
            'lead_id' => $lead?->lead_code,
            'initials' => $this->initials($project->project_name),
            'po_tone' => $this->statusTone($project->po_status),
            'project_tone' => $this->statusTone($project->project_status),
        ];
    }

    private function mapReport(EmployeeReport $report): array
    {
        $activityTotal = $report->leads_contacted
            + $report->follow_ups_completed
            + $report->notes_added
            + $report->quotations_shared
            + $report->calls_made
            + $report->whatsapp_messages
            + $report->emails_sent;

        return [
            'id' => $report->id,
            'employee' => $report->employee?->name ?? 'Unknown employee',
            'employee_email' => $report->employee?->email ?? 'Not available',
            'manager' => $report->manager?->name ?? $report->employee?->manager?->name ?? 'Owner/Admin',
            'report_date' => $this->formatDate($report->report_date),
            'submitted_on' => $this->formatDateTime($report->updated_at),
            'hours' => $this->formatTimeRange($report->work_started_at, $report->work_ended_at),
            'leads_contacted' => $report->leads_contacted,
            'follow_ups_completed' => $report->follow_ups_completed,
            'notes_added' => $report->notes_added,
            'quotations_shared' => $report->quotations_shared,
            'calls_made' => $report->calls_made,
            'whatsapp_messages' => $report->whatsapp_messages,
            'emails_sent' => $report->emails_sent,
            'activity_total' => $activityTotal,
            'summary' => $report->summary ?: 'No summary added.',
            'issues' => $report->issues ?: 'No blockers reported.',
            'tomorrow_plan' => $report->tomorrow_plan ?: 'No plan added.',
            'status' => $report->status,
            'status_tone' => $this->statusTone($report->status),
            'manager_feedback' => $report->manager_feedback,
        ];
    }

    private function leadSources(User $user): array
    {
        $counts = $this->visibleLeadQuery($user)
            ->select('source')
            ->get()
            ->groupBy(fn (Lead $lead) => $lead->source ?: 'Others')
            ->map->count();

        $total = max(1, $counts->sum());
        $palette = [
            'IndiaMART' => '#f97316',
            'Website' => '#fbbf24',
            'Alibaba' => '#34d399',
            'WhatsApp' => '#93c5fd',
            'Others' => '#c084fc',
        ];

        return collect(['IndiaMART', 'Website', 'Alibaba', 'WhatsApp', 'Others'])
            ->map(function (string $source) use ($counts, $palette, $total): array {
                $count = $counts[$source] ?? 0;
                $percentage = (int) round(($count / $total) * 100);

                return [
                    'label' => $source,
                    'percentage' => $percentage.'%',
                    'color' => $palette[$source],
                ];
            })
            ->all();
    }

    private function leadTrend(User $user): array
    {
        $months = collect(range(5, 0))->map(fn (int $offset) => now()->copy()->startOfMonth()->subMonths($offset));

        $counts = $months->map(function (Carbon $month) use ($user): array {
            $count = $this->visibleLeadQuery($user)
                ->whereBetween('inquiry_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->count();

            return [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        });

        $maxCount = max(1, (int) $counts->max('count'));

        return $counts->map(fn (array $point): array => [
            'month' => $point['month'],
            'value' => $point['count'],
            'bar_height' => max(12, (int) round(($point['count'] / $maxCount) * 100)),
        ])->all();
    }

    private function integrationCards(User $user): array
    {
        $counts = $this->visibleLeadQuery($user)
            ->select('source')
            ->get()
            ->groupBy(fn (Lead $lead) => $lead->source ?: 'Other')
            ->map->count();

        return collect([
            [
                'source' => 'IndiaMART',
                'method' => 'Push/Pull API',
                'state' => 'Connect first',
                'detail' => 'Use seller CRM key and webhook URL for real-time enquiries.',
            ],
            [
                'source' => 'TradeIndia',
                'method' => 'API or CSV',
                'state' => 'Ready next',
                'detail' => 'Map buyer inquiry fields into the same lead intake queue.',
            ],
            [
                'source' => 'Alibaba',
                'method' => 'Export/API review',
                'state' => 'Needs approval',
                'detail' => 'Start with inquiry exports while API access is confirmed.',
            ],
            [
                'source' => 'Aajjo',
                'method' => 'CSV/API',
                'state' => 'Ready later',
                'detail' => 'Import seller dashboard leads until account API access is available.',
            ],
        ])->map(fn (array $card): array => [
            ...$card,
            'count' => (int) ($counts[$card['source']] ?? 0),
            'tone' => $this->sourceTone($card['source']),
        ])->all();
    }

    private function assignmentFlow(User $user): array
    {
        return [
            [
                'step' => 'Capture',
                'label' => 'Live source intake',
                'value' => $this->visibleLeadQuery($user)->whereNotNull('external_source_id')->count().' platform refs',
            ],
            [
                'step' => 'Manager',
                'label' => 'Admin routes lead',
                'value' => $this->visibleLeadQuery($user)->whereNotNull('manager_user_id')->count().' manager-owned',
            ],
            [
                'step' => 'Employee',
                'label' => 'Manager assigns team',
                'value' => $this->visibleLeadQuery($user)->whereNotNull('assigned_to_user_id')->count().' employee-owned',
            ],
            [
                'step' => 'Follow-up',
                'label' => 'Status and reminders',
                'value' => $this->visibleFollowUpQuery($user)->where('status', '!=', 'Completed')->count().' pending',
            ],
        ];
    }

    private function routingRules(): array
    {
        return [
            ['rule' => 'IndiaMART + Gujarat', 'owner' => 'Rahul Sharma', 'reason' => 'Regional manager handles fastest first call.'],
            ['rule' => 'Alibaba or export inquiry', 'owner' => 'Anil Kumar', 'reason' => 'Route international/RFQ style leads to export desk.'],
            ['rule' => 'Packaging machinery', 'owner' => 'Pooja Shah', 'reason' => 'Assign product-specific employee when requirement is clear.'],
            ['rule' => 'Duplicate mobile number', 'owner' => 'Previous owner', 'reason' => 'Keep customer context with the same person.'],
        ];
    }

    private function assignmentRule(Lead $lead): string
    {
        if ($lead->source === 'IndiaMART' && $lead->state === 'Gujarat') {
            return 'IndiaMART + Gujarat';
        }

        if ($lead->source === 'Alibaba') {
            return 'Alibaba export lead';
        }

        if ($lead->source === 'TradeIndia') {
            return 'TradeIndia buyer inquiry';
        }

        if ($lead->source === 'Aajjo') {
            return 'Aajjo instant product lead';
        }

        if (Str::contains(Str::lower((string) $lead->requirement_category), 'packaging')) {
            return 'Packaging product owner';
        }

        return 'Manual review';
    }

    private function leadStatusOptions(): array
    {
        return collect([
            'New',
            'Contacted',
            'Interested',
            'Negotiation',
            'PO Received',
            'Lost',
        ])
            ->merge($this->distinctLeadValues('status'))
            ->unique()
            ->values()
            ->all();
    }

    private function teamOptions(array $roles): array
    {
        return User::query()
            ->whereIn('role', $roles)
            ->where('employment_status', 'Active')
            ->orderByRaw("CASE role WHEN 'Manager' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get(['id', 'name', 'role'])
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ])
            ->all();
    }

    private function managerOptions(User $user): array
    {
        if ($this->isOwner($user)) {
            return $this->teamOptions(['Manager']);
        }

        if ($this->isManager($user)) {
            return [[
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]];
        }

        return [];
    }

    private function employeeOptions(User $user): array
    {
        if ($this->isOwner($user)) {
            return $this->teamOptions(['Manager', 'Agent']);
        }

        if ($this->isManager($user)) {
            return User::query()
                ->where(function (Builder $builder) use ($user): void {
                    $builder
                        ->where('id', $user->id)
                        ->orWhere('manager_id', $user->id);
                })
                ->where('employment_status', 'Active')
                ->orderByRaw("CASE role WHEN 'Manager' THEN 0 ELSE 1 END")
                ->orderBy('name')
                ->get(['id', 'name', 'role'])
                ->map(fn (User $teamUser): array => [
                    'id' => $teamUser->id,
                    'name' => $teamUser->name,
                    'role' => $teamUser->role,
                ])
                ->all();
        }

        return [[
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
        ]];
    }

    private function distinctVisibleLeadValues(User $user, string $column): array
    {
        return $this->visibleLeadQuery($user)
            ->whereNotNull($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->filter()
            ->values()
            ->all();
    }

    private function distinctLeadValues(string $column): array
    {
        return Lead::query()
            ->whereNotNull($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->filter()
            ->values()
            ->all();
    }

    private function distinctManagerNames(User $user): array
    {
        if ($this->isAgent($user)) {
            return [];
        }

        if ($this->isManager($user)) {
            return [$user->name];
        }

        return User::query()
            ->where('role', 'Manager')
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    private function distinctOwnerNames(User $user): array
    {
        if ($this->isAgent($user)) {
            return [];
        }

        return $this->visibleTeamQuery($user)
            ->whereHas('assignedLeads')
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    private function projectStatusOptions(): array
    {
        $projectStatuses = Project::query()->distinct()->pluck('project_status')->filter()->all();
        $poStatuses = Project::query()->distinct()->pluck('po_status')->filter()->all();

        return collect([...$projectStatuses, ...$poStatuses])
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private function projectOwnerNames(User $user): array
    {
        return $this->visibleTeamQuery($user)
            ->whereHas('ownedProjects')
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    private function headerRangeValue(User $user): string
    {
        $latest = $this->visibleLeadQuery($user)->max('inquiry_at');

        return $latest ? Carbon::parse($latest)->format('F Y') : now()->format('F Y');
    }

    private function dateRangeText(User $user): string
    {
        $min = $this->visibleLeadQuery($user)->min('inquiry_at');
        $max = $this->visibleLeadQuery($user)->max('inquiry_at');

        if (! $min || ! $max) {
            return now()->format('d M Y');
        }

        return Carbon::parse($min)->format('d M Y').' - '.Carbon::parse($max)->format('d M Y');
    }

    private function visibleLeadQuery(User $user): Builder
    {
        return $this->applyLeadVisibility(Lead::query(), $user);
    }

    private function visibleProjectQuery(User $user): Builder
    {
        $query = Project::query();

        if ($this->isOwner($user)) {
            return $query;
        }

        if ($this->isManager($user)) {
            $teamIds = $this->teamUserIds($user);

            return $query->where(function (Builder $builder) use ($user, $teamIds): void {
                $builder
                    ->where('owner_id', $user->id)
                    ->orWhereHas('lead', function (Builder $leadQuery) use ($user, $teamIds): void {
                        $leadQuery
                            ->where('manager_user_id', $user->id)
                            ->orWhereIn('assigned_to_user_id', $teamIds);
                    });
            });
        }

        return $query->whereHas('lead', fn (Builder $leadQuery) => $leadQuery->where('assigned_to_user_id', $user->id));
    }

    private function visibleFollowUpQuery(User $user): Builder
    {
        $query = FollowUp::query();

        if ($this->isOwner($user)) {
            return $query;
        }

        if ($this->isManager($user)) {
            $teamIds = $this->teamUserIds($user);

            return $query->where(function (Builder $builder) use ($user, $teamIds): void {
                $builder
                    ->whereIn('assigned_to_user_id', $teamIds)
                    ->orWhereHas('lead', function (Builder $leadQuery) use ($user, $teamIds): void {
                        $leadQuery
                            ->where('manager_user_id', $user->id)
                            ->orWhereIn('assigned_to_user_id', $teamIds);
                    });
            });
        }

        return $query->where(function (Builder $builder) use ($user): void {
            $builder
                ->where('assigned_to_user_id', $user->id)
                ->orWhereHas('lead', fn (Builder $leadQuery) => $leadQuery->where('assigned_to_user_id', $user->id));
        });
    }

    private function visibleReportQuery(User $user): Builder
    {
        $query = EmployeeReport::query();

        if ($this->isOwner($user)) {
            return $query;
        }

        if ($this->isManager($user)) {
            return $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('manager_id', $user->id)
                    ->orWhereHas('employee', fn (Builder $employeeQuery) => $employeeQuery->where('manager_id', $user->id));
            });
        }

        return $query->where('user_id', $user->id);
    }

    private function visibleTeamQuery(User $user): Builder
    {
        $query = User::query()->with('manager');

        if ($this->isOwner($user)) {
            return $query;
        }

        if ($this->isManager($user)) {
            return $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('id', $user->id)
                    ->orWhere('manager_id', $user->id);
            });
        }

        return $query->where('id', $user->id);
    }

    private function applyLeadVisibility(Builder $query, User $user): Builder
    {
        if ($this->isOwner($user)) {
            return $query;
        }

        if ($this->isManager($user)) {
            $teamIds = $this->teamUserIds($user);

            return $query->where(function (Builder $builder) use ($user, $teamIds): void {
                $builder
                    ->where('manager_user_id', $user->id)
                    ->orWhereIn('assigned_to_user_id', $teamIds);
            });
        }

        return $query->where('assigned_to_user_id', $user->id);
    }

    private function teamUserIds(User $manager): array
    {
        if (! $this->isManager($manager)) {
            return [$manager->id];
        }

        return User::query()
            ->where('id', $manager->id)
            ->orWhere('manager_id', $manager->id)
            ->pluck('id')
            ->all();
    }

    private function dashboardKicker(User $user): string
    {
        return match ($user->role) {
            'Admin/Owner' => 'OWNER OVERVIEW',
            'Manager' => 'MANAGER OVERVIEW',
            'Agent' => 'MY WORK',
            default => 'CRM OVERVIEW',
        };
    }

    private function dashboardTitle(User $user): string
    {
        return match ($user->role) {
            'Manager' => 'Team Dashboard',
            'Agent' => 'My Dashboard',
            default => 'Dashboard',
        };
    }

    private function dashboardSubtitle(User $user): string
    {
        return match ($user->role) {
            'Manager' => 'Your team leads, follow-ups, and project movement',
            'Agent' => 'Your assigned leads, reminders, and active work',
            default => 'Overview of your business',
        };
    }

    private function assignmentSubtitle(User $user): string
    {
        return match ($user->role) {
            'Manager' => 'Assign your manager-owned leads to agents and keep status updated.',
            'Agent' => 'Work only on the leads assigned to you and update status as you progress.',
            default => 'Match the incoming queue with the right manager, employee, and status.',
        };
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

    private function formatDate(mixed $value): string
    {
        if (! $value) {
            return 'Not available';
        }

        return Carbon::parse($value)->format('d M Y');
    }

    private function formatDateInput(mixed $value): string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : now()->toDateString();
    }

    private function formatDateTime(mixed $value): string
    {
        if (! $value) {
            return 'Not available';
        }

        return Carbon::parse($value)->format('d M Y, h:i A');
    }

    private function formatTime(mixed $value): string
    {
        if (! $value) {
            return 'Not available';
        }

        return Carbon::parse($value)->format('h:i A');
    }

    private function formatTimeInput(mixed $value, string $default = ''): string
    {
        return $value ? Carbon::parse($value)->format('H:i') : $default;
    }

    private function formatTimeRange(mixed $start, mixed $end): string
    {
        if (! $start && ! $end) {
            return 'Not added';
        }

        return $this->formatTime($start).' - '.$this->formatTime($end);
    }

    private function trendText(int|float $current, int|float $previous): string
    {
        if ((float) $previous === 0.0) {
            return $current > 0 ? 'New activity this month' : 'No month-over-month change';
        }

        $percent = (($current - $previous) / $previous) * 100;
        $rounded = (int) round($percent);

        if ($rounded === 0) {
            return 'No change from last month';
        }

        return ($rounded > 0 ? '+' : '').$rounded.'% from last month';
    }

    private function moneyTrend(float $current, float $previous): string
    {
        if ($previous <= 0) {
            return $current > 0 ? 'Revenue now tracked in MySQL' : 'No revenue captured yet';
        }

        $percent = (int) round((($current - $previous) / $previous) * 100);

        return ($percent > 0 ? '+' : '').$percent.'% from last month';
    }

    private function money(float $value): string
    {
        $formatted = number_format($value, 0, '.', ',');
        $parts = explode(',', $formatted);

        if (strlen($parts[0]) <= 3) {
            return 'Rs '.$formatted;
        }

        $lastThree = substr($formatted, -3);
        $rest = substr(str_replace(',', '', $formatted), 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

        return 'Rs '.$rest.','.$lastThree;
    }

    private function initials(string $value): string
    {
        return Str::of($value)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    private function sourceTone(?string $source): string
    {
        return match ($source) {
            'IndiaMART' => 'orange',
            'Website' => 'blue',
            'Alibaba' => 'mint',
            'WhatsApp' => 'green',
            'TradeIndia' => 'violet',
            'Aajjo' => 'amber',
            default => 'slate',
        };
    }

    private function statusTone(?string $status): string
    {
        return match ($status) {
            'New' => 'blue',
            'Contacted' => 'slate',
            'Interested' => 'amber',
            'Negotiation' => 'violet',
            'PO Received', 'In Production', 'Dispatch Ready', 'Completed', 'Active' => 'green',
            'Approved' => 'green',
            'Submitted' => 'blue',
            'Reviewed' => 'violet',
            'Need clarification' => 'amber',
            'Pending' => 'amber',
            'Due Soon' => 'rose',
            'Lost', 'Inactive' => 'rose',
            default => 'slate',
        };
    }
}
