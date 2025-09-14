<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Mail\CustomResetPasswordMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Mail::fake();

        $user = User::factory()->create(['role' => 'resident']);

        $this->post('/forgot-password', ['email' => $user->email]);

        Mail::assertSent(CustomResetPasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        $token = Password::createToken($user);
        
        $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        $token = Password::createToken($user);
        
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));
    }
}
