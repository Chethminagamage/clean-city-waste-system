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
            if (!Schema::hasColumn('feedback', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('feedback', 'waste_report_id')) {
                $table->foreignId('waste_report_id')->nullable()->constrained('waste_reports')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('feedback', 'rating')) {
                $table->integer('rating')->nullable(); // 1-5 star rating
            }
            
            if (!Schema::hasColumn('feedback', 'message')) {
                $table->text('message')->nullable();
            }
            
            if (!Schema::hasColumn('feedback', 'feedback_type')) {
                $table->string('feedback_type')->default('report'); // 'report', 'service', etc.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['waste_report_id', 'rating']);
        });
    }
};
