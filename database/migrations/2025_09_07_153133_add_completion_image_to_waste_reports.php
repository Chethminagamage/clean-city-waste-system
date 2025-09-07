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
            $table->string('completion_image_path')->nullable()->after('image_path');
            $table->text('completion_notes')->nullable()->after('additional_details');
            $table->timestamp('completion_image_uploaded_at')->nullable()->after('collected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->dropColumn(['completion_image_path', 'completion_notes', 'completion_image_uploaded_at']);
        });
    }
};
