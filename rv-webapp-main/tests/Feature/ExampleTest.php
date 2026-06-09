<?php

namespace Tests\Feature;

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
}
