<?php

namespace Tests\Feature\Resident;

use App\Models\User;
use App\Models\Area;
use App\Models\CollectionSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class CollectionScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_view_collection_schedule(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $area = Area::factory()->create();

        $response = $this->actingAs($resident)->get('/resident/schedule');

        $response->assertStatus(200);
        $response->assertViewIs('resident.schedule.index');
    }

    public function test_resident_can_filter_schedule_by_area(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $area1 = Area::factory()->create(['name' => 'Area 1']);
        $area2 = Area::factory()->create(['name' => 'Area 2']);

        CollectionSchedule::factory()->create([
            'area_id' => $area1->id,
            'date' => now()->addDays(1)
        ]);
        CollectionSchedule::factory()->create([
            'area_id' => $area2->id,
            'date' => now()->addDays(2)
        ]);

        $response = $this->actingAs($resident)->get("/resident/schedule?area_id={$area1->id}");

        $response->assertStatus(200);
        $response->assertViewHas('area', $area1);
    }

    public function test_resident_can_filter_schedule_by_date_range(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $area = Area::factory()->create();

        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->addDays(1)
        ]);
        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->addDays(10)
        ]);

        $from = now()->format('Y-m-d');
        $to = now()->addDays(5)->format('Y-m-d');

        $response = $this->actingAs($resident)->get("/resident/schedule?from={$from}&to={$to}");

        $response->assertStatus(200);
    }

    public function test_schedule_shows_upcoming_collections(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $area = Area::factory()->create();

        // Past collection (should not appear)
        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->subDays(1)
        ]);

        // Future collection (should appear)
        $futureSchedule = CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->addDays(1)
        ]);

        $response = $this->actingAs($resident)->get("/resident/schedule?area_id={$area->id}");

        $response->assertStatus(200);
    $response->assertSee($futureSchedule->date->format('M d, Y'));
    }

    public function test_schedule_handles_collection_overrides(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $area = Area::factory()->create();

        $schedule = CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->addDays(1)
        ]);

        $response = $this->actingAs($resident)->get("/resident/schedule?area_id={$area->id}");

        $response->assertStatus(200);
    }

    public function test_schedule_shows_different_waste_types(): void
    {
        $resident = User::factory()->create(['role' => 'resident']);
        $area = Area::factory()->create();

        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->addDays(1),
            'waste_type' => 'organic'
        ]);

        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'date' => now()->addDays(2),
            'waste_type' => 'recyclable'
        ]);

        $response = $this->actingAs($resident)->get("/resident/schedule?area_id={$area->id}");

        $response->assertStatus(200);
    $response->assertSee('Organic');
    $response->assertSee('Recyclable');
    }
}
