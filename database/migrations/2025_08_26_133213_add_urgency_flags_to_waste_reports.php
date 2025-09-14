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
            $table->boolean('is_urgent')->default(false);
            $table->timestamp('urgent_reported_at')->nullable();
            $table->text('urgent_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->dropColumn(['is_urgent', 'urgent_reported_at', 'urgent_message']);
        });
    }
};
