<?php

namespace Tests\Feature\Collector;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CollectorDashboardTest extends TestCase
{
   use RefreshDatabase;

   protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_collector_can_access_dashboard(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($collector, 'collector')->get('/collector/dashboard');

        $response->assertOk();
        $response->assertViewIs('collector.dashboard');
    }

    public function test_collector_sees_assigned_reports(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $otherCollector = User::factory()->create(['role' => 'collector']);
        
        $myReport = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);
        $otherReport = WasteReport::factory()->create([
            'collector_id' => $otherCollector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/dashboard');

        $response->assertOk();
        $response->assertSee($myReport->location);
        $response->assertDontSee($otherReport->location);
    }

    public function test_collector_can_start_collection(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($collector, 'collector')->post("/collector/report/{$report->id}/start");

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'enroute'
        ]);
    }

    public function test_collector_can_complete_collection(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'enroute'
        ]);

        // Note: There might not be a generic 'complete' route - only 'complete-with-image'
        // Let's use complete-with-image for now
        $file = UploadedFile::fake()->image('completion.jpg');
        
        $response = $this->actingAs($collector, 'collector')->post("/collector/report/{$report->id}/complete-with-image", [
            'completion_notes' => 'Collection completed',
            'completion_image' => $file
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'collected'
        ]);
    }

    public function test_collector_can_complete_with_image(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'enroute'
        ]);
        $image = UploadedFile::fake()->image('completion.jpg');

        $response = $this->actingAs($collector, 'collector')->post("/collector/report/{$report->id}/complete-with-image", [
            'completion_image' => $image,
            'completion_notes' => 'Collection completed successfully'
        ]);

        $response->assertSuccessful();
        
        $report->refresh();
        $this->assertEquals('collected', $report->status);
        $this->assertNotNull($report->completion_image_path);
        $this->assertEquals('Collection completed successfully', $report->completion_notes);
        
        // Check that the actual file was stored (using the path stored in the database)
        Storage::disk('public')->assertExists($report->completion_image_path);
    }

    public function test_collector_cannot_access_other_collectors_reports(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $otherCollector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $otherCollector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($collector, 'collector')->post("/collector/report/{$report->id}/start");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_collector_can_view_report_details(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($collector, 'collector')->get("/collector/report/{$report->id}/details");

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'location',
            'waste_type',
            'status'
        ]);
    }

    public function test_collector_can_update_profile(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($collector, 'collector')->put('/collector/profile', [
            'name' => 'Updated Collector Name',
            'email' => $collector->email, // Keep existing email
            'phone' => '123-456-7890'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $collector->id,
            'name' => 'Updated Collector Name',
            'contact' => '123-456-7890'
        ]);
    }

    public function test_collector_can_update_password(): void
    {
        $collector = User::factory()->create([
            'role' => 'collector',
            'password' => bcrypt('old-password')
        ]);

        $response = $this->actingAs($collector, 'collector')->put('/collector/password', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password'
        ]);

        $response->assertRedirect();
        $this->assertTrue(\Hash::check('new-password', $collector->fresh()->password));
    }

    public function test_collector_profile_update_fails_with_wrong_current_password(): void
    {
        $collector = User::factory()->create([
            'role' => 'collector',
            'password' => bcrypt('old-password')
        ]);

        $response = $this->actingAs($collector, 'collector')->put('/collector/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_collector_can_view_all_reports(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        WasteReport::factory()->count(5)->create(['collector_id' => $collector->id]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/reports');

        $response->assertOk();
    }

    public function test_resident_cannot_access_collector_dashboard(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($resident)->get('/collector/dashboard');

        $response->assertRedirect();
    }
}
