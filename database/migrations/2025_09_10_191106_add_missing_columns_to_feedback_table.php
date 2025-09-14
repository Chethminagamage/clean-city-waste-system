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
        Schema::table('feedback', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('feedback', 'response_read_at')) {
                $table->timestamp('response_read_at')->nullable();
            }
            if (!Schema::hasColumn('feedback', 'subject')) {
                $table->string('subject')->nullable();
            }
            if (!Schema::hasColumn('feedback', 'type')) {
                $table->string('type')->default('general');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['response_read_at', 'subject', 'type']);
        });
    }
};
