<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Area;
use App\Models\CollectionSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AdminCollectionScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_collection_schedules(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();
        
        CollectionSchedule::factory()->count(3)->create([
            'area_id' => $area->id
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/schedules');

        $response->assertStatus(200);
        $response->assertViewIs('admin.schedules.index');
    }

    public function test_admin_can_create_collection_schedule(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/schedules', [
            'area_id' => $area->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
            'frequency' => 'weekly',
            'waste_type' => 'organic',
            'notes' => 'Regular collection schedule'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collection_schedules', [
            'area_id' => $area->id,
            'waste_type' => 'organic',
            'notes' => 'Regular collection schedule'
        ]);
    }

    public function test_admin_can_view_schedule_details(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();
        $schedule = CollectionSchedule::factory()->create([
            'area_id' => $area->id
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/schedules/{$schedule->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.schedules.show');
        $response->assertViewHas('schedule', $schedule);
    }

    public function test_admin_can_edit_collection_schedule(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();
        $schedule = CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'waste_type' => 'organic'
        ]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/schedules/{$schedule->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.schedules.edit');
        $response->assertViewHas('schedule', $schedule);
    }

    public function test_admin_can_update_collection_schedule(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();
        $schedule = CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'waste_type' => 'organic'
        ]);

        $response = $this->actingAs($admin, 'admin')->put("/admin/schedules/{$schedule->id}", [
            'area_id' => $area->id,
            'date' => now()->addDays(5)->format('Y-m-d'),
            'frequency' => 'weekly',
            'waste_type' => 'recyclable',
            'notes' => 'Updated schedule'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collection_schedules', [
            'id' => $schedule->id,
            'waste_type' => 'recyclable',
            'notes' => 'Updated schedule'
        ]);
    }

    public function test_admin_can_delete_collection_schedule(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();
        $schedule = CollectionSchedule::factory()->create([
            'area_id' => $area->id
        ]);

        $response = $this->actingAs($admin, 'admin')->delete("/admin/schedules/{$schedule->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('collection_schedules', [
            'id' => $schedule->id
        ]);
    }

    public function test_admin_can_generate_automated_schedules(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/schedules/generate', [
            'area_id' => $area->id,
            'type' => 'urban'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collection_schedules', [
            'area_id' => $area->id
        ]);
    }

    public function test_schedule_creation_requires_valid_data(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/schedules', [
            'area_id' => '', // Missing area
            'date' => '', // Missing date
            'waste_type' => '',  // Missing waste type
            'frequency' => '' // Missing frequency
        ]);

        $response->assertSessionHasErrors(['area_id', 'date', 'waste_type', 'frequency']);
    }

    public function test_admin_cannot_create_schedule_for_past_date(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/schedules', [
            'area_id' => $area->id,
            'date' => now()->subDays(1)->format('Y-m-d'), // Past date
            'frequency' => 'weekly',
            'waste_type' => 'organic'
        ]);

        $response->assertSessionHasErrors(['date']);
    }

    public function test_admin_can_filter_schedules_by_area(): void
    {
        $admin = Admin::factory()->create();
        $area1 = Area::factory()->create(['name' => 'Area 1']);
        $area2 = Area::factory()->create(['name' => 'Area 2']);

        CollectionSchedule::factory()->create(['area_id' => $area1->id]);
        CollectionSchedule::factory()->create(['area_id' => $area2->id]);

        $response = $this->actingAs($admin, 'admin')->get("/admin/schedules?area_id={$area1->id}");

        $response->assertStatus(200);
    }

    public function test_admin_can_filter_schedules_by_date_range(): void
    {
        $admin = Admin::factory()->create();
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

        $response = $this->actingAs($admin, 'admin')->get("/admin/schedules?from={$from}&to={$to}");

        $response->assertStatus(200);
    }

    public function test_admin_can_filter_schedules_by_waste_type(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();

        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'waste_type' => 'organic'
        ]);
        CollectionSchedule::factory()->create([
            'area_id' => $area->id,
            'waste_type' => 'recyclable'
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/schedules?waste_type=organic');

        $response->assertStatus(200);
    }

    public function test_generated_schedules_respect_frequency(): void
    {
        $admin = Admin::factory()->create();
        $area = Area::factory()->create();

        $this->actingAs($admin, 'admin')->post('/admin/schedules/generate', [
            'area_id' => $area->id,
            'type' => 'urban'
        ]);

        // Urban schedules should create multiple schedules (organic + recyclable)
        $scheduleCount = CollectionSchedule::where('area_id', $area->id)->count();
        $this->assertGreaterThan(0, $scheduleCount);
        
        // Verify that both organic and recyclable schedules are created
        $organicCount = CollectionSchedule::where('area_id', $area->id)
            ->where('waste_type', 'organic')->count();
        $recyclableCount = CollectionSchedule::where('area_id', $area->id)
            ->where('waste_type', 'recyclable')->count();
            
        $this->assertGreaterThan(0, $organicCount);
        $this->assertGreaterThan(0, $recyclableCount);
    }
}
