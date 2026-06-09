<?php

namespace Tests\Feature;

use App\Models\EmployeeReport;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_login_page_loads(): void
    {
        $this->seed();

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Welcome Back!')
            ->assertSee('LeadFlow')
            ->assertSee('Sign in to continue to LeadFlow CRM')
            ->assertSee('owner@leadflowcrm.com')
            ->assertSee('12345');
    }

    public function test_dashboard_redirects_guests_to_login(): void
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));
    }

    public function test_sign_in_redirects_to_dashboard(): void
    {
        $this->seed();

        $this->post('/login', [
            'username' => 'owner@leadflowcrm.com',
            'password' => '12345',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_user_can_view_core_pages(): void
    {
        $this->seed();
        $user = User::query()->where('email', 'owner@leadflowcrm.com')->firstOrFail();
        $session = ['crm_user_id' => $user->id];

        $this->withSession($session)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('OWNER OVERVIEW')
            ->assertSee('Leads by Source');

        $this->withSession($session)
            ->get('/assign-leads')
            ->assertOk()
            ->assertSee('Live Lead Intake')
            ->assertSee('IndiaMART')
            ->assertSee('Select manager')
            ->assertSee('Leads List')
            ->assertSee('Rahul Patel');

        $this->withSession($session)
            ->get('/projects')
            ->assertOk()
            ->assertSee('Projects List')
            ->assertSee('Project Details');

        $this->withSession($session)
            ->get('/follow-ups')
            ->assertOk()
            ->assertSee('Follow-up List')
            ->assertSee('Closest reminder first');

        $this->withSession($session)
            ->get('/reports')
            ->assertOk()
            ->assertSee('Team Daily Reports')
            ->assertSee('Neha Verma')
            ->assertSee('Sanjay Patel');

        $this->withSession($session)
            ->get('/employees')
            ->assertOk()
            ->assertSee('Manage your employees')
            ->assertSee('Rahul Sharma');

        $this->withSession($session)
            ->get('/settings')
            ->assertOk()
            ->assertSee('Integration Readiness');

        $this->withSession($session)
            ->get('/leads/LF2601')
            ->assertOk()
            ->assertSee('Lead Details')
            ->assertSee('Activity Timeline');

        $this->withSession($session)
            ->get('/leads/create')
            ->assertOk()
            ->assertSee('Add New Lead')
            ->assertSee('Draft State');
    }

    public function test_authenticated_user_can_update_lead_assignment(): void
    {
        $this->seed();

        $owner = User::query()->where('email', 'owner@leadflowcrm.com')->firstOrFail();
        $manager = User::query()->where('email', 'anil.kumar@leadflowcrm.com')->firstOrFail();
        $employee = User::query()->where('email', 'pooja.shah@leadflowcrm.com')->firstOrFail();
        $lead = Lead::query()->where('lead_code', 'LF2601')->firstOrFail();

        $this->withSession(['crm_user_id' => $owner->id])
            ->post(route('assign-leads.assignment.update', ['lead' => $lead->id]), [
                'manager_user_id' => $manager->id,
                'assigned_to_user_id' => $employee->id,
                'status' => 'Contacted',
            ])
            ->assertSessionHas('assignment_updated')
            ->assertRedirect();

        $lead->refresh();

        $this->assertSame($manager->id, $lead->manager_user_id);
        $this->assertSame($employee->id, $lead->assigned_to_user_id);
        $this->assertSame('Contacted', $lead->status);
    }

    public function test_manager_sees_team_queue_without_owner_settings(): void
    {
        $this->seed();

        $manager = User::query()->where('email', 'rahul.sharma@leadflowcrm.com')->firstOrFail();

        $this->withSession(['crm_user_id' => $manager->id])
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('MANAGER OVERVIEW')
            ->assertSee('Reports')
            ->assertDontSee('Settings');

        $this->withSession(['crm_user_id' => $manager->id])
            ->get('/assign-leads')
            ->assertOk()
            ->assertSee('Assign your manager-owned leads')
            ->assertDontSee('Select manager')
            ->assertSee('Select employee');

        $this->withSession(['crm_user_id' => $manager->id])
            ->get('/settings')
            ->assertForbidden();
    }

    public function test_manager_can_assign_only_their_team_members(): void
    {
        $this->seed();

        $manager = User::query()->where('email', 'rahul.sharma@leadflowcrm.com')->firstOrFail();
        $teamMember = User::query()->where('email', 'pooja.shah@leadflowcrm.com')->firstOrFail();
        $outsideAgent = User::query()->where('email', 'sanjay.patel@leadflowcrm.com')->firstOrFail();
        $lead = Lead::query()->where('lead_code', 'LF2601')->firstOrFail();

        $this->withSession(['crm_user_id' => $manager->id])
            ->post(route('assign-leads.assignment.update', ['lead' => $lead->id]), [
                'assigned_to_user_id' => $teamMember->id,
                'status' => 'Interested',
            ])
            ->assertSessionHas('assignment_updated')
            ->assertRedirect();

        $lead->refresh();

        $this->assertSame($manager->id, $lead->manager_user_id);
        $this->assertSame($teamMember->id, $lead->assigned_to_user_id);
        $this->assertSame('Interested', $lead->status);

        $this->withSession(['crm_user_id' => $manager->id])
            ->from('/assign-leads')
            ->post(route('assign-leads.assignment.update', ['lead' => $lead->id]), [
                'assigned_to_user_id' => $outsideAgent->id,
                'status' => 'Negotiation',
            ])
            ->assertSessionHasErrors('assigned_to_user_id')
            ->assertRedirect('/assign-leads');
    }

    public function test_agent_only_sees_personal_work_and_updates_status(): void
    {
        $this->seed();

        $agent = User::query()->where('email', 'neha.verma@leadflowcrm.com')->firstOrFail();
        $ownLead = Lead::query()->where('lead_code', 'LF2603')->firstOrFail();
        $otherLead = Lead::query()->where('lead_code', 'LF2601')->firstOrFail();

        $this->withSession(['crm_user_id' => $agent->id])
            ->get('/assign-leads')
            ->assertOk()
            ->assertSee('My Leads')
            ->assertSee('Submit Report')
            ->assertSee('Work only on the leads assigned to you')
            ->assertSee('Neha Gupta')
            ->assertDontSee('Live Lead Intake')
            ->assertDontSee('Select manager')
            ->assertDontSee('Select employee')
            ->assertDontSee('+ Add Lead');

        $this->withSession(['crm_user_id' => $agent->id])
            ->get('/projects')
            ->assertForbidden();

        $this->withSession(['crm_user_id' => $agent->id])
            ->get('/employees')
            ->assertForbidden();

        $this->withSession(['crm_user_id' => $agent->id])
            ->post(route('assign-leads.assignment.update', ['lead' => $ownLead->id]), [
                'status' => 'Contacted',
            ])
            ->assertSessionHas('assignment_updated')
            ->assertRedirect();

        $this->assertSame('Contacted', $ownLead->refresh()->status);

        $this->withSession(['crm_user_id' => $agent->id])
            ->post(route('assign-leads.assignment.update', ['lead' => $otherLead->id]), [
                'status' => 'Lost',
            ])
            ->assertForbidden();
    }

    public function test_agent_can_submit_daily_report(): void
    {
        $this->seed();

        $agent = User::query()->where('email', 'neha.verma@leadflowcrm.com')->firstOrFail();

        $this->withSession(['crm_user_id' => $agent->id])
            ->get('/reports')
            ->assertOk()
            ->assertSee('Submit Daily Report')
            ->assertSee('Rahul Sharma + Owner')
            ->assertDontSee('Review status');

        $this->withSession(['crm_user_id' => $agent->id])
            ->post(route('reports.store'), [
                'report_date' => '2026-06-09',
                'work_started_at' => '09:15',
                'work_ended_at' => '18:45',
                'leads_contacted' => 6,
                'follow_ups_completed' => 2,
                'notes_added' => 3,
                'quotations_shared' => 1,
                'calls_made' => 7,
                'whatsapp_messages' => 8,
                'emails_sent' => 3,
                'summary' => 'Updated assigned leads and shared quotation details.',
                'issues' => 'One quotation needs manager confirmation.',
                'tomorrow_plan' => 'Close pending customer confirmation.',
            ])
            ->assertSessionHas('report_saved')
            ->assertRedirect();

        $report = EmployeeReport::query()
            ->where('user_id', $agent->id)
            ->whereDate('report_date', '2026-06-09')
            ->firstOrFail();

        $this->assertSame($agent->manager_id, $report->manager_id);
        $this->assertSame(6, $report->leads_contacted);
        $this->assertSame('Submitted', $report->status);
    }

    public function test_manager_reviews_only_team_reports(): void
    {
        $this->seed();

        $manager = User::query()->where('email', 'rahul.sharma@leadflowcrm.com')->firstOrFail();
        $teamReport = EmployeeReport::query()
            ->whereHas('employee', fn ($query) => $query->where('email', 'neha.verma@leadflowcrm.com'))
            ->firstOrFail();
        $outsideReport = EmployeeReport::query()
            ->whereHas('employee', fn ($query) => $query->where('email', 'sanjay.patel@leadflowcrm.com'))
            ->firstOrFail();

        $this->withSession(['crm_user_id' => $manager->id])
            ->get('/reports')
            ->assertOk()
            ->assertSee('Team Daily Reports')
            ->assertSee('Neha Verma')
            ->assertSee('Pooja Shah')
            ->assertDontSee('Sanjay Patel');

        $this->withSession(['crm_user_id' => $manager->id])
            ->post(route('reports.review', ['report' => $teamReport->id]), [
                'status' => 'Approved',
                'manager_feedback' => 'Approved for today.',
            ])
            ->assertSessionHas('report_reviewed')
            ->assertRedirect();

        $this->assertSame('Approved', $teamReport->refresh()->status);
        $this->assertSame('Approved for today.', $teamReport->manager_feedback);

        $this->withSession(['crm_user_id' => $manager->id])
            ->post(route('reports.review', ['report' => $outsideReport->id]), [
                'status' => 'Reviewed',
            ])
            ->assertForbidden();
    }

    public function test_owner_can_review_all_employee_reports(): void
    {
        $this->seed();

        $owner = User::query()->where('email', 'owner@leadflowcrm.com')->firstOrFail();
        $outsideReport = EmployeeReport::query()
            ->whereHas('employee', fn ($query) => $query->where('email', 'sanjay.patel@leadflowcrm.com'))
            ->firstOrFail();

        $this->withSession(['crm_user_id' => $owner->id])
            ->get('/reports')
            ->assertOk()
            ->assertSee('Neha Verma')
            ->assertSee('Sanjay Patel');

        $this->withSession(['crm_user_id' => $owner->id])
            ->post(route('reports.review', ['report' => $outsideReport->id]), [
                'status' => 'Need clarification',
                'manager_feedback' => 'Please add freight estimate status.',
            ])
            ->assertSessionHas('report_reviewed')
            ->assertRedirect();

        $this->assertSame('Need clarification', $outsideReport->refresh()->status);
    }
}
