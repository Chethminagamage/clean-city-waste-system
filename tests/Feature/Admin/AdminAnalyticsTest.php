<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\WasteReport;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_analytics_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $response->assertViewIs('admin.analytics');
    }

    public function test_analytics_shows_collector_efficiency_metrics(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create completed reports
        WasteReport::factory()->count(3)->create([
            'collector_id' => $collector->id,
            'status' => 'collected',
            'created_at' => now()->subDays(10)
        ]);

        // Create pending reports
        WasteReport::factory()->count(2)->create([
            'collector_id' => $collector->id,
            'status' => 'assigned',
            'created_at' => now()->subDays(5)
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $response->assertViewHas('collectorMetrics');
        
        $collectorMetrics = $response->viewData('collectorMetrics');
        $this->assertNotEmpty($collectorMetrics);
    }

    public function test_analytics_can_filter_by_time_range(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics?range=7');

        $response->assertStatus(200);
        $response->assertViewHas('timeRange', 7);
    }

    public function test_analytics_can_filter_by_specific_collector(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($admin, 'admin')->get("/admin/analytics?collector={$collector->id}");

        $response->assertStatus(200);
        $response->assertViewHas('collectorId', $collector->id);
    }

    public function test_analytics_shows_overview_statistics(): void
    {
        $admin = Admin::factory()->create();
        
        // Create various reports with different statuses
        WasteReport::factory()->create(['status' => 'pending']);
        WasteReport::factory()->create(['status' => 'collected']);
        WasteReport::factory()->create(['status' => 'assigned']);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $response->assertViewHas('overviewStats');
        
        $overviewStats = $response->viewData('overviewStats');
        $this->assertArrayHasKey('total_reports', $overviewStats);
        $this->assertArrayHasKey('completed_reports', $overviewStats);
        $this->assertArrayHasKey('pending_reports', $overviewStats);
    }

    public function test_analytics_shows_performance_trends(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);

        // Create reports over different days
        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'collected',
            'created_at' => now()->subDays(7)
        ]);
        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'collected',
            'created_at' => now()->subDays(3)
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $response->assertViewHas('performanceTrends');
    }

    public function test_collector_efficiency_calculates_completion_rate(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);

        // 3 completed out of 5 total = 60% completion rate
        WasteReport::factory()->count(3)->create([
            'collector_id' => $collector->id,
            'status' => 'collected'
        ]);
        WasteReport::factory()->count(2)->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $collectorMetrics = $response->viewData('collectorMetrics');
        
        $collectorData = $collectorMetrics->firstWhere('id', $collector->id);
        $this->assertEquals(60.0, $collectorData['completion_rate']);
    }

    public function test_analytics_includes_collector_ratings(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);
        $resident = User::factory()->create(['role' => 'resident']);

        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'resident_id' => $resident->id,
            'status' => 'collected'
        ]);

        // Create feedback with rating
        Feedback::factory()->create([
            'user_id' => $resident->id,
            'waste_report_id' => $report->id,
            'rating' => 5
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $collectorMetrics = $response->viewData('collectorMetrics');
        
        $collectorData = $collectorMetrics->firstWhere('id', $collector->id);
        $this->assertEquals(5.0, $collectorData['avg_rating']);
    }

    public function test_analytics_calculates_efficiency_score(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);

        // Create a perfect scenario: high completion rate, good rating
        WasteReport::factory()->count(5)->create([
            'collector_id' => $collector->id,
            'status' => 'collected',
            'collected_at' => now()
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $collectorMetrics = $response->viewData('collectorMetrics');
        
        $collectorData = $collectorMetrics->firstWhere('id', $collector->id);
        $this->assertArrayHasKey('efficiency_score', $collectorData);
        $this->assertIsNumeric($collectorData['efficiency_score']);
    }

    public function test_analytics_shows_recent_activity(): void
    {
        $admin = Admin::factory()->create();
        $collector = User::factory()->create(['role' => 'collector']);

        WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'collected',
            'updated_at' => now()->subHours(2)
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/analytics');

        $response->assertStatus(200);
        $collectorMetrics = $response->viewData('collectorMetrics');
        
        $collectorData = $collectorMetrics->firstWhere('id', $collector->id);
        $this->assertArrayHasKey('recent_activity', $collectorData);
    }
}
