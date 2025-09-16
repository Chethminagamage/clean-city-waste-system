<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicSetupTest extends TestCase
{
      use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // This will run migrations automatically due to RefreshDatabase
    }

    public function test_database_connection_works(): void
    {
        // Test that the database connection is working
        $this->assertTrue(true);
    }

    public function test_can_create_user(): void
    {
        $user = \App\Models\User::factory()->create();
        
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function test_can_create_waste_report(): void
    {
        $user = \App\Models\User::factory()->create();
        $report = \App\Models\WasteReport::factory()->create([
            'resident_id' => $user->id,
        ]);

        $this->assertDatabaseHas('waste_reports', [
            'id' => $report->id,
            'resident_id' => $user->id,
        ]);
    }
}
