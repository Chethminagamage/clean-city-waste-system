<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Update the enum to include 'cancelled'
        DB::statement("
          ALTER TABLE waste_reports
          MODIFY status ENUM('pending','assigned','in_progress','collected','closed','cancelled')
          NOT NULL DEFAULT 'pending'
        ");

        // Optional: store when it was cancelled
        DB::statement("ALTER TABLE waste_reports ADD COLUMN cancelled_at TIMESTAMP NULL AFTER closed_at");
    }

    public function down(): void
    {
        // Rollback enum to previous set (adjust if yours is different)
        DB::statement("
          ALTER TABLE waste_reports
          MODIFY status ENUM('pending','assigned','in_progress','collected','closed')
          NOT NULL DEFAULT 'pending'
        ");

        DB::statement("ALTER TABLE waste_reports DROP COLUMN cancelled_at");
    }
};
