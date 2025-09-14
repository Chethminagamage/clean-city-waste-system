<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // For SQLite compatibility, we'll add columns without modifying enums
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite approach: Add cancelled_at column directly
            Schema::table('waste_reports', function (Blueprint $table) {
                $table->timestamp('cancelled_at')->nullable();
            });
        } else {
            // MySQL approach: Update enum and add cancelled_at
            DB::statement("
              ALTER TABLE waste_reports
              MODIFY status ENUM('pending','assigned','in_progress','collected','closed','cancelled')
              NOT NULL DEFAULT 'pending'
            ");
            DB::statement("ALTER TABLE waste_reports ADD COLUMN cancelled_at TIMESTAMP NULL AFTER closed_at");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            Schema::table('waste_reports', function (Blueprint $table) {
                $table->dropColumn('cancelled_at');
            });
        } else {
            // Rollback enum to previous set (adjust if yours is different)
            DB::statement("
              ALTER TABLE waste_reports
              MODIFY status ENUM('pending','assigned','in_progress','collected','closed')
              NOT NULL DEFAULT 'pending'
            ");
            
            DB::statement("ALTER TABLE waste_reports DROP COLUMN cancelled_at");
        }
    }
};
