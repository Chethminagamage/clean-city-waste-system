<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('collection_schedules', function (Blueprint $table) {
            // nullable first so we can backfill safely, then you can make it notNullable later if you want
            $table->foreignId('area_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('areas')
                  ->nullOnDelete(); // if an area is deleted, schedules become NULL instead of blocked
        });
    }

    public function down(): void {
        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('area_id');
        });
    }
};
