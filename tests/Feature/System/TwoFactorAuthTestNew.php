<?php

namespace Tests\Feature\System;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_otp_with_valid_code(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id, '2fa:otp' => '123456']);

        $response = $this->postJson('/2fa/verify', [
            'otp' => '123456'
        ]);

        $response->assertJson(['success' => true]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_verify_with_invalid_otp(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id, '2fa:otp' => '123456']);

        $response = $this->postJson('/2fa/verify', [
            'otp' => '654321'
        ]);

        $response->assertJson(['error' => 'Invalid OTP']);
        $response->assertStatus(422);
        $this->assertGuest();
    }

    public function test_user_cannot_verify_without_session(): void
    {
        $user = User::factory()->create(['role' => 'resident']);

        $response = $this->postJson('/2fa/verify', [
            'otp' => '123456'
        ]);

        $response->assertJson(['error' => 'No active 2FA session']);
        $response->assertStatus(401);
    }

    public function test_user_can_resend_otp(): void
    {
        $user = User::factory()->create([
            'role' => 'resident',
            'email' => 'test@example.com'
        ]);

        session(['2fa:user:id' => $user->id, '2fa:otp' => '123456']);

        $response = $this->postJson('/2fa/resend');

        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['message']);
    }

    public function test_otp_verification_requires_valid_code(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id]);

        $response = $this->postJson('/2fa/verify', [
            'otp' => '' // Empty code
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['otp']);
    }

    public function test_otp_code_must_be_numeric(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id]);

        $response = $this->postJson('/2fa/verify', [
            'otp' => 'abcdef'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['otp']);
    }

    public function test_otp_code_must_be_correct_length(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id]);

        $response = $this->postJson('/2fa/verify', [
            'otp' => '123' // Too short
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['otp']);
    }

    public function test_resend_otp_generates_new_code(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id, '2fa:otp' => '123456']);
        $originalOtp = session('2fa:otp');

        $this->postJson('/2fa/resend');

        $newOtp = session('2fa:otp');
        $this->assertNotEquals($originalOtp, $newOtp);
    }

    public function test_successful_otp_verification_clears_code(): void
    {
        $user = User::factory()->create(['role' => 'resident']);
        
        session(['2fa:user:id' => $user->id, '2fa:otp' => '123456']);

        $this->postJson('/2fa/verify', [
            'otp' => '123456'
        ]);

        $this->assertNull(session('2fa:otp'));
        $this->assertNull(session('2fa:user:id'));
    }

    public function test_guest_cannot_access_2fa_endpoints(): void
    {
        $response = $this->postJson('/2fa/verify', [
            'otp' => '123456'
        ]);

        $response->assertStatus(401);
    }
}
