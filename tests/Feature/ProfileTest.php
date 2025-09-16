<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
        use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertStatus(302); // Should redirect to role-specific profile
        $response->assertRedirect('/resident/profile/edit');
    }

    public function test_resident_profile_edit_page_is_displayed(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this
            ->actingAs($user)
            ->get('/resident/profile/edit');

        $response->assertOk();
    }

    public function test_resident_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this
            ->actingAs($user)
            ->post('/resident/profile/update', [
                'action' => 'info',
                'name' => 'Test User Updated',
                'email' => 'updated@example.com',
                'contact' => '123-456-7890',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $user->refresh();

        $this->assertSame('Test User Updated', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertSame('123-456-7890', $user->contact);
    }

    public function test_admin_profile_page_redirects_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get('/profile');

        $response->assertStatus(302);
        $response->assertRedirect('/admin/profile');
    }
}
