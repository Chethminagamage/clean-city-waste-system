<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->date('date')->after('area_id');
            $table->time('window_from')->nullable()->after('date');
            $table->time('window_to')->nullable()->after('window_from');
            $table->string('waste_type', 50)->nullable()->after('window_to'); // e.g. Organic, Plastic, E-Waste
            $table->string('notes', 255)->nullable()->after('waste_type');
        });
    }

    public function down(): void {
        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->dropColumn(['date', 'window_from', 'window_to', 'waste_type', 'notes']);
        });
    }
};
