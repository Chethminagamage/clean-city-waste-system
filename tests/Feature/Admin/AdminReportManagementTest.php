<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_bin_reports(): void
    {
        $admin = Admin::factory()->create();
        WasteReport::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/binreports');

        $response->assertStatus(200);
        $response->assertViewIs('admin.binreports');
    }

    public function test_admin_can_assign_collector_manually(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'status' => 'pending',
            'collector_id' => null
        ]);

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

    public function test_admin_can_auto_assign_nearest_collector(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create([
            'role' => 'collector',
            'status' => 1,
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);
        $report = WasteReport::factory()->create([
            'status' => 'pending',
            'collector_id' => null,
            'latitude' => 40.7130,
            'longitude' => -74.0062
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/binreports/assign/{$report->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);
    }

    public function test_admin_can_get_nearby_collectors(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create([
            'role' => 'collector',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);
        $report = WasteReport::factory()->create([
            'latitude' => 40.7130,
            'longitude' => -74.0062
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/report/{$report->id}/nearby-collectors");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'distance',
                'latitude',
                'longitude'
            ]
        ]);
    }

    public function test_admin_can_close_completed_report(): void
    {
        $admin = Admin::factory()->create();
        $report = WasteReport::factory()->create([
            'status' => 'collected'
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/reports/{$report->id}/close");

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'closed'
        ]);
    }

    public function test_admin_can_view_report_details(): void
    {
        $admin = Admin::factory()->create();
        $report = WasteReport::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get("/admin/reports/{$report->id}");

        $response->assertStatus(200);
    $response->assertViewIs('admin.report_details');
        $response->assertViewHas('report', $report);
    }

    public function test_admin_can_cancel_report(): void
    {
        $admin = Admin::factory()->create();
        $report = WasteReport::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin, 'admin')->post("/admin/reports/{$report->id}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_admin_can_add_note_to_report(): void
    {
        $admin = Admin::factory()->create();
        $report = WasteReport::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post("/admin/reports/{$report->id}/add-note", [
            'note' => 'This report requires special attention'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
        ]);
        $this->assertStringContainsString(
            'This report requires special attention',
            \App\Models\WasteReport::find($report->id)->admin_notes
        );
    }

}
