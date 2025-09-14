<?php

namespace Tests\Feature\System;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_notifications(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        $notification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['message' => 'Test notification'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($user)->post("/notifications/{$notification->id}/read");

        $response->assertRedirect();
        
        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        // Create multiple unread notifications
        DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['message' => 'Test notification 1'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['message' => 'Test notification 2'],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($user)->post('/notifications/read-all');

        $response->assertRedirect();
        
        $unreadCount = $user->unreadNotifications()->count();
        $this->assertEquals(0, $unreadCount);
    }

    public function test_user_can_view_individual_notification(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        $notification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => [
                'message' => 'Test notification',
                'action_url' => '/test-url'
            ],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($user)->get("/notifications/{$notification->id}");

        $response->assertStatus(200);
        $response->assertViewIs('notifications.show');
        $response->assertViewHas('notification', function ($viewNotification) use ($notification) {
            return (string)$viewNotification->id === (string)$notification->id;
        });
    }

    public function test_notification_opens_and_marks_as_read(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        $notification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => [
                'message' => 'Test notification',
                'action_url' => '/test-url'
            ],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($user)->get("/notifications/{$notification->id}");

        $response->assertStatus(200);
        
        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    public function test_admin_can_view_notifications(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/notifications');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_other_users_notifications(): void
    {
        $user1 = User::factory()->create(['role' => 'resident']);
        $user2 = User::factory()->create(['role' => 'resident']);
        
        $notification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user2->id,
            'data' => ['message' => 'Test notification'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($user1)->get("/notifications/{$notification->id}");

        $response->assertStatus(404);
    }

    public function test_notifications_are_ordered_by_newest_first(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        $oldNotification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['message' => 'Old notification'],
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2)
        ]);

        $newNotification = DatabaseNotification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => ['message' => 'New notification'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['New notification', 'Old notification']);
    }

    public function test_notification_count_is_accurate(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        // Create 3 unread notifications
        for ($i = 0; $i < 3; $i++) {
            DatabaseNotification::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\TestNotification',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => ['message' => "Test notification {$i}"],
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->assertEquals(3, $user->unreadNotifications()->count());
    }

    public function test_guest_cannot_access_notifications(): void
    {
        $response = $this->get('/notifications');

        $response->assertRedirect('/login');
    }
}
