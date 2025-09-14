<?php

namespace Tests\Feature\Gamification;

use App\Models\User;
use App\Models\WasteReport;
use App\Models\PointTransaction;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GamificationTest extends TestCase
{
    use RefreshDatabase;

    protected GamificationService $gamificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gamificationService = app(GamificationService::class);
    }

    public function test_user_gets_points_for_submitting_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        // Submit a waste report which should award points
        $response = $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '123 Test Street',
            'latitude' => '10.123456',
            'longitude' => '20.654321',
            'report_date' => now()->format('Y-m-d'),
            'waste_type' => 'Organic',
            'additional_details' => 'Test details',
            'image' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg')
        ]);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $resident->id,
            'source' => 'report_submitted'
        ]);

        $resident->refresh();
        $resident->load('gamification');
        $this->assertTrue($resident->gamification && $resident->gamification->total_points > 0);
    }

    public function test_user_gets_bonus_points_for_first_report(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        // Submit first report
        $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '123 Test Street',
            'latitude' => '10.123456',
            'longitude' => '20.654321',
            'report_date' => now()->format('Y-m-d'),
            'waste_type' => 'Organic',
            'image' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg')
        ]);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $resident->id,
            'source' => 'first_report'
        ]);
    }

    public function test_user_gets_points_when_report_is_collected(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $collector = User::factory()->create(['role' => 'collector']);
        
        $report = WasteReport::factory()->create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
            'status' => 'enroute'
        ]);

        // Complete the collection (authenticate as collector with proper guard)
        $file = UploadedFile::fake()->image('completion.jpg');
        $this->actingAs($collector, 'collector')->post("/collector/report/{$report->id}/complete-with-image", [
            'completion_image' => $file,
            'completion_notes' => 'Collection completed'
        ]);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $resident->id,
            'source' => 'report_collected'
        ]);
    }

    public function test_gamification_service_awards_points_correctly(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $points = $this->gamificationService->awardPoints(
            $user,
            'report_submitted',
            null,
            'Test point award'
        );

        $this->assertGreaterThan(0, $points);
        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $user->id,
            'source' => 'report_submitted',
            'description' => 'Test point award'
        ]);

        $user->refresh();
        $user->load('gamification');
        $this->assertEquals($points, $user->gamification->total_points);
    }

    public function test_user_can_view_achievements(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->get('/resident/gamification');

        $response->assertOk();
        $response->assertViewIs('resident.gamification.index');
    }

    public function test_user_can_view_rewards(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->get('/resident/rewards');

        $response->assertOk();
        $response->assertViewIs('resident.gamification.rewards');
    }

    public function test_user_current_level_is_calculated_correctly(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        // Create gamification record with points
        $gamification = $user->getOrCreateGamification();
        $gamification->update(['total_points' => 150]);

        $stats = $this->gamificationService->getUserStats($user);

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('current_level', $stats);
        $this->assertArrayHasKey('current_rank', $stats);
        $this->assertArrayHasKey('total_points', $stats);
        $this->assertEquals(150, $stats['total_points']);
    }

    public function test_points_are_awarded_for_proper_segregation(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        // Submit report with proper segregation (organic waste)
        $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '123 Test Street',
            'latitude' => '10.123456',
            'longitude' => '20.654321',
            'report_date' => now()->format('Y-m-d'),
            'waste_type' => 'organic', // Properly categorized
            'image' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg')
        ]);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $resident->id,
            'source' => 'proper_segregation'
        ]);
    }

    public function test_bonus_points_for_urgent_reports(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);

        $this->actingAs($resident)->post('/resident/report/store', [
            'location' => '123 Test Street',
            'latitude' => '10.123456',
            'longitude' => '20.654321',
            'report_date' => now()->format('Y-m-d'),
            'waste_type' => 'Hazardous',
            'is_urgent' => true,
            'image' => \Illuminate\Http\UploadedFile::fake()->image('test.jpg')
        ]);

        $this->assertDatabaseHas('point_transactions', [
            'user_id' => $resident->id,
            'source' => 'urgent_report'
        ]);
    }
}
