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
        // For SQLite compatibility, skip enum modifications
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE waste_reports MODIFY COLUMN status ENUM('pending','assigned','enroute','collected','closed','cancelled')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite compatibility, skip enum modifications
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE waste_reports MODIFY COLUMN status ENUM('pending','assigned','collected','closed','cancelled')");
        }
    }
};
