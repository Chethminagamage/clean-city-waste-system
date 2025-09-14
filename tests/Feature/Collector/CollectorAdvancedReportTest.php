<?php

namespace Tests\Feature\Collector;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectorAdvancedReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_collector_can_view_completed_reports_history(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        WasteReport::factory()->count(3)->create([
            'collector_id' => $collector->id,
            'status' => 'collected'
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/reports/completed');

        $response->assertStatus(200);
        $response->assertViewIs('collector.completed-reports');
        $response->assertViewHas('completedReports');
    }

    public function test_collector_can_update_location(): void
    {
        $collector = User::factory()->create([
            'role' => 'collector',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);

        $response = $this->actingAs($collector, 'collector')->post('/collector/update-location', [
            'latitude' => 40.7580,
            'longitude' => -73.9855,
            'location' => 'Times Square, NYC'
        ]);

        $response->assertJson(['success' => true]);
        
        $collector->refresh();
        $this->assertEquals(40.7580, $collector->latitude);
        $this->assertEquals(-73.9855, $collector->longitude);
        $this->assertEquals('Times Square, NYC', $collector->location);
    }

    public function test_collector_can_confirm_report_assignment(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($collector, 'collector')->post("/collector/report/{$report->id}/confirm");

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'enroute' // matches controller logic
        ]);
    }

    public function test_collector_can_get_report_details_via_api(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'resident_id' => $resident->id
        ]);

        $response = $this->actingAs($collector, 'collector')->get("/collector/report/{$report->id}/details");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'reference_code',
            'waste_type',
            'location',
            'latitude',
            'longitude',
            'status',
            'priority',
            'description',
            'created_at',
            'updated_at',
            'resident' => [
                'name',
                'email',
                'contact'
            ]
        ]);
    }

    public function test_collector_can_filter_reports_by_status(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);
        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'collected'
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/reports?status=assigned');

        $response->assertStatus(200);
    }

    public function test_collector_can_search_reports(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'location' => 'Main Street Park'
        ]);
        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'location' => 'Downtown Plaza'
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/reports?search=Main');

        $response->assertStatus(200);
    }

    public function test_location_update_requires_valid_coordinates(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($collector, 'collector')->postJson('/collector/update-location', [
            'latitude' => 'invalid',
            'longitude' => 'invalid'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['latitude', 'longitude']);
    }

    public function test_collector_can_view_reports_on_map(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        WasteReport::factory()->count(3)->create([
            'collector_id' => $collector->id,
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('collectorLat');
        $response->assertViewHas('collectorLng');
        $response->assertViewHas('activeReports');
    }

    public function test_collector_cannot_access_other_collectors_reports(): void
    {
        $collector1 = User::factory()->create(['role' => 'collector']);
        $collector2 = User::factory()->create(['role' => 'collector']);
        
        $report = WasteReport::factory()->create([
            'collector_id' => $collector2->id
        ]);

        $response = $this->actingAs($collector1, 'collector')->get("/collector/report/{$report->id}/details");

        $response->assertStatus(404);
    }

    public function test_collector_report_statistics_are_calculated(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create reports with different statuses
        WasteReport::factory()->count(5)->create([
            'collector_id' => $collector->id,
            'status' => 'collected'
        ]);
        WasteReport::factory()->count(2)->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/profile');

        $response->assertStatus(200);
        $response->assertViewHas('totalReports', 7);
        $response->assertViewHas('completedReports', 5);
        $response->assertViewHas('activeReports', 2);
    }



    public function test_collector_can_view_report_history_with_pagination(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create many reports to test pagination
        WasteReport::factory()->count(25)->create([
            'collector_id' => $collector->id,
            'status' => 'collected'
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/reports/completed?page=2');

        $response->assertStatus(200);
    }

    public function test_collector_dashboard_shows_performance_metrics(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create performance data
        WasteReport::factory()->count(10)->create([
            'collector_id' => $collector->id,
            'status' => 'collected',
            'created_at' => now()->subDays(30),
            'collected_at' => now()->subDays(29)
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/dashboard');

        $response->assertStatus(200);
        // Should include performance metrics in the view
    }
}
