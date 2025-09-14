<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First update any existing in_progress records to collected
        DB::table('waste_reports')->where('status', 'in_progress')->update(['status' => 'collected']);
        
        // Then modify the enum to remove in_progress (skip for SQLite)
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE waste_reports MODIFY COLUMN status ENUM('pending','assigned','enroute','collected','closed','cancelled')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip for SQLite compatibility
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE waste_reports MODIFY COLUMN status ENUM('pending','assigned','enroute','in_progress','collected','closed','cancelled')");
        }
    }
};
