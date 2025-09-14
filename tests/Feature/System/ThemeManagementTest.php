<?php

namespace Tests\Feature\System;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_theme(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->post('/theme/toggle');

        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['theme']);
    }

    public function test_user_can_set_specific_theme(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->post('/theme/set', [
            'theme' => 'dark'
        ]);

        $response->assertJson([
            'success' => true,
            'theme' => 'dark'
        ]);
    }

    public function test_user_can_get_current_theme(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->get('/theme/current');

        $response->assertStatus(200);
        $response->assertJsonStructure(['theme']);
    }

    public function test_theme_persists_across_sessions(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        // Set theme to dark
        $this->actingAs($user)->post('/theme/set', ['theme' => 'dark']);

        // Get current theme
        $response = $this->actingAs($user)->get('/theme/current');

        $response->assertJson(['theme' => 'dark']);
    }

    public function test_admin_can_use_theme_management(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/theme/toggle');

        $response->assertJson(['success' => true]);
    }

    public function test_collector_can_use_theme_management(): void
    {
        $collector = User::factory()->create(['role' => 'collector']);

        $response = $this->actingAs($collector, 'collector')->post('/theme/toggle');

        $response->assertJson(['success' => true]);
    }

    public function test_theme_validation_requires_valid_theme(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->actingAs($user)->postJson('/theme/set', [
            'theme' => 'invalid_theme'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['theme']);
    }

    public function test_guest_cannot_access_theme_management(): void
    {
        $response = $this->post('/theme/toggle');

        $response->assertRedirect('/login');
    }
}
