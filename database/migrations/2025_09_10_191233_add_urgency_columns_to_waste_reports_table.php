<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('waste_reports', 'is_urgent')) {
                $table->boolean('is_urgent')->default(false);
            }
            if (!Schema::hasColumn('waste_reports', 'urgent_message')) {
                $table->text('urgent_message')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->dropColumn(['is_urgent', 'urgent_message']);
        });
    }
};
