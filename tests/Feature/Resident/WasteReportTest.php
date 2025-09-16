<?php

namespace Tests\Feature\Resident;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WasteReportTest extends TestCase
{
 use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_resident_can_submit_waste_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $image = UploadedFile::fake()->image('waste.jpg');

        $response = $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '123 Test Street',
            'latitude' => '10.123456',
            'longitude' => '20.654321',
            'report_date' => now()->format('Y-m-d'),
            'waste_type' => 'Organic',
            'additional_details' => 'Large pile of organic waste',
            'image' => $image,
        ]);

        $response->assertRedirect('/resident/dashboard');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('waste_reports', [
            'resident_id' => $resident->id,
            'location' => '123 Test Street',
            'waste_type' => 'Organic',
            'status' => 'pending',
        ]);

        Storage::disk('public')->assertExists('reports/' . $image->hashName());
    }

    public function test_waste_report_submission_requires_valid_data(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '',
            'waste_type' => '',
            'image' => 'invalid-file',
        ]);

        $response->assertSessionHasErrors(['location', 'waste_type', 'image']);
        $this->assertDatabaseCount('waste_reports', 0);
    }

    public function test_waste_report_requires_valid_image(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '123 Test Street',
            'latitude' => '10.123456',
            'longitude' => '20.654321',
            'report_date' => now()->format('Y-m-d'),
            'waste_type' => 'Organic',
            'image' => UploadedFile::fake()->create('document.pdf', 1000),
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseCount('waste_reports', 0);
    }

    public function test_resident_can_view_their_reports(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $reports = WasteReport::factory(3)->create(['resident_id' => $resident->id]);

        $response = $this->actingAs($resident)->get('/resident/reports');

        $response->assertSuccessful();
        $response->assertViewIs('resident.reports.index');
        // The view loads data via AJAX, so no 'reports' data is passed initially
    }

    public function test_resident_can_cancel_pending_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($resident)->post("/resident/reports/{$report->id}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'cancelled'
        ]);
    }

    public function test_resident_cannot_cancel_assigned_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($resident)->post("/resident/reports/{$report->id}/cancel");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'status' => 'assigned'
        ]);
    }

    public function test_resident_can_duplicate_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->pending()->create(['resident_id' => $resident->id]);

        $response = $this->actingAs($resident)->post("/resident/reports/{$report->id}/duplicate");

        $response->assertSuccessful();
        $response->assertJson(['ok' => true]);
        $this->assertDatabaseCount('waste_reports', 2);
        
        $duplicatedReport = WasteReport::latest()->first();
        $this->assertEquals($report->location, $duplicatedReport->location);
        $this->assertEquals($report->waste_type, $duplicatedReport->waste_type);
        $this->assertEquals('pending', $duplicatedReport->status);
    }

    public function test_resident_cannot_access_other_residents_reports(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $otherResident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create(['resident_id' => $otherResident->id]);

        $response = $this->actingAs($resident)->post("/resident/reports/{$report->id}/cancel");

        $response->assertForbidden();
    }

    public function test_resident_can_export_reports_csv(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        WasteReport::factory()->count(3)->create(['resident_id' => $resident->id]);

        $response = $this->actingAs($resident)->get('/resident/reports/export/csv');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_resident_dashboard_shows_reports_and_stats(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        WasteReport::factory()->count(5)->create(['resident_id' => $resident->id, 'status' => 'pending']);
        WasteReport::factory()->count(3)->create(['resident_id' => $resident->id, 'status' => 'collected']);

        $response = $this->actingAs($resident)->get('/resident/dashboard');

        $response->assertOk();
        $response->assertViewHas('reports');
    }  
}
