<?php

namespace Tests\Feature\Resident;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_resident_can_view_report_history(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $reports = WasteReport::factory()->count(3)->create([
            'resident_id' => $resident->id
        ]);

        $response = $this->actingAs($resident)->get('/resident/reports');

        $response->assertStatus(200);
        $response->assertViewIs('resident.reports.index');
    }

    public function test_resident_can_search_reports(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $report1 = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'location' => 'Main Street Park'
        ]);
        $report2 = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'location' => 'Downtown Area'
        ]);

        $response = $this->actingAs($resident)->get('/resident/reports/data?q=Main');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data['data']);
    }

    public function test_resident_can_filter_reports_by_status(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'status' => 'pending'
        ]);
        WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'status' => 'collected'
        ]);

        $response = $this->actingAs($resident)->get('/resident/reports/data?status=pending');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data['data']);
    }

    public function test_resident_can_filter_reports_by_date_range(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'created_at' => now()->subDays(10)
        ]);
        WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'created_at' => now()->subDays(2)
        ]);

        $from = now()->subDays(5)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->actingAs($resident)->get("/resident/reports/data?from={$from}&to={$to}");

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(1, $data['data']);
    }

    public function test_resident_can_cancel_report(): void
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

    public function test_resident_can_duplicate_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id
        ]);

        $response = $this->actingAs($resident)->post("/resident/reports/{$report->id}/duplicate");

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
        $this->assertDatabaseCount('waste_reports', 2);
    }

    public function test_resident_can_export_reports_to_csv(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        WasteReport::factory()->count(3)->create([
            'resident_id' => $resident->id
        ]);

        $response = $this->actingAs($resident)->get('/resident/reports/export/csv');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_resident_can_view_individual_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id
        ]);

        $response = $this->actingAs($resident)->get("/resident/reports/{$report->id}");

        $response->assertStatus(200);
        $response->assertViewIs('resident.reports.show');
        $response->assertViewHas('report', $report);
    }

    public function test_resident_can_download_report_pdf(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id
        ]);

        $response = $this->actingAs($resident)->get("/resident/reports/{$report->id}/pdf");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_resident_can_mark_report_as_urgent(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        // Set created_at in the past to allow urgent marking
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'is_urgent' => false,
            'status' => 'pending',
            'created_at' => now()->subHours(5)
        ]);

        $response = $this->actingAs($resident)->post("/resident/reports/{$report->id}/urgent", [
            'message' => 'This needs immediate attention'
        ]);

        $response->assertStatus(302);
        $report->refresh();
        $this->assertTrue((bool)$report->is_urgent);
        $this->assertEquals('This needs immediate attention', $report->urgent_message);
    }

    public function test_resident_cannot_access_other_residents_reports(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $otherResident = User::factory()->create(['role' => 'resident']);
        $report = WasteReport::factory()->create([
            'resident_id' => $otherResident->id
        ]);

        $response = $this->actingAs($resident)->get("/resident/reports/{$report->id}");

        $response->assertStatus(404);
    }
}
