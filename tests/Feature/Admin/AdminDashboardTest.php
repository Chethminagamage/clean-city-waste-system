<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
    }

    public function test_admin_dashboard_shows_correct_stats(): void
    {
        $admin = Admin::factory()->create();
        
        // Create test data
        WasteReport::factory()->count(10)->create(['status' => 'pending']);
        WasteReport::factory()->count(5)->create(['status' => 'collected']);
        WasteReport::factory()->count(3)->create(['status' => 'assigned']);

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertOk();
        
        // Check if stats are passed to view
        $response->assertViewHas('totalReports', 18);
        $response->assertViewHas('completedReports', 5);
        $response->assertViewHas('pendingReports', 10);
    }

    public function test_admin_can_view_all_reports(): void
    {
        $admin = Admin::factory()->create();
        $resident = User::factory()->create(['role' => 'resident']);
        
        $report1 = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'location' => 'Test Location 1'
        ]);
        $report2 = WasteReport::factory()->create([
            'resident_id' => $resident->id, 
            'location' => 'Test Location 2'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/binreports');

        $response->assertOk();
        $response->assertSee('Test Location 1');
        $response->assertSee('Test Location 2');
    }

    public function test_admin_can_manage_collectors(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($admin, 'admin')->get('/admin/collectors');

        $response->assertOk();
        $response->assertSee($collector->name);
    }

    public function test_admin_can_view_system_analytics(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertOk();
        $response->assertViewIs('admin.analytics');
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user, 'web')->get('/admin/dashboard');

        // Currently redirects to regular login - this is the current behavior
        // TODO: Should ideally redirect to admin login
        $response->assertRedirect('/login');
    }

    public function test_admin_can_assign_collector_to_report(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin, 'admin')->post("/admin/assign-collector/{$report->id}", [
            'collector_id' => $collector->id
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);
    }

    public function test_admin_login_works(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('admin');
    }

    public function test_admin_can_logout(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
             ->post('/admin/logout')
             ->assertRedirect('/admin/login');

        $this->assertGuest('admin');
    }
}
