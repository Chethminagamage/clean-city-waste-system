<?php

namespace Tests\Feature\Collector;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class CollectorNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_collector_can_view_notifications(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($collector, 'collector')->get('/collector/notifications');

        $response->assertStatus(200);
        $response->assertViewIs('collector.notifications.index');
    }

    public function test_collector_can_get_recent_notifications(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create a notification for the collector
        DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => [
                'message' => 'New report assigned to you',
                'report_id' => 1
            ],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/notifications/recent');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'type',
                'data',
                'created_at'
            ]
        ]);
    }

    public function test_collector_can_get_unread_notification_count(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create unread notifications
        DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => ['message' => 'Test notification'],
            'read_at' => null, // Unread
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/notifications/unread-count');

        $response->assertStatus(200);
        $response->assertJson(['count' => 1]);
    }

    public function test_collector_can_mark_notification_as_read(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        $notification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => ['message' => 'Test notification'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($collector, 'collector')->post("/collector/notifications/{$notification->id}/mark-read");

        $response->assertJson(['success' => true]);
        
        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    public function test_collector_can_mark_all_notifications_as_read(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        // Create multiple unread notifications
        DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => ['message' => 'Test notification 1'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => ['message' => 'Test notification 2'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($collector, 'collector')->post('/collector/notifications/mark-all-read');

        $response->assertJson(['success' => true]);
        
        $unreadCount = $collector->unreadNotifications()->count();
        $this->assertEquals(0, $unreadCount);
    }

    public function test_notification_data_includes_required_fields(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        $report = WasteReport::factory()->create([
            'collector_id' => $collector->id
        ]);
        
        $notification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => [
                'message' => 'New report assigned to you',
                'report_id' => $report->id,
                'type' => 'report_assigned',
                'action_url' => "/collector/report/{$report->id}"
            ],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/notifications/recent');

        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertArrayHasKey('message', $data[0]['data']);
        $this->assertArrayHasKey('report_id', $data[0]['data']);
        $this->assertArrayHasKey('type', $data[0]['data']);
    }

    public function test_notifications_are_ordered_by_newest_first(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);
        
        $oldNotification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => ['message' => 'Old notification'],
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2)
        ]);

        $newNotification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\ReportAssigned',
            'notifiable_type' => User::class,
            'notifiable_id' => $collector->id,
            'data' => ['message' => 'New notification'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($collector, 'collector')->get('/collector/notifications/recent');

        $response->assertStatus(200);
        $data = $response->json();
        
        // First notification should be the newest one
        $this->assertEquals('New notification', $data[0]['data']['message']);
        $this->assertEquals('Old notification', $data[1]['data']['message']);
    }
}
