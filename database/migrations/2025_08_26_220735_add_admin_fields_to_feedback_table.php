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
            // Admin response fields
            $table->text('admin_response')->nullable()->after('message');
            $table->unsignedBigInteger('admin_responded_by')->nullable()->after('admin_response');
            $table->timestamp('admin_responded_at')->nullable()->after('admin_responded_by');
            
            // Feedback status tracking
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending')->after('admin_responded_at');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('status');
            $table->timestamp('resolved_at')->nullable()->after('resolved_by');
            
            // Foreign key constraints (assuming you have an admins table)
            $table->foreign('admin_responded_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('resolved_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['admin_responded_by']);
            $table->dropForeign(['resolved_by']);
            
            // Drop columns
            $table->dropColumn([
                'admin_response',
                'admin_responded_by',
                'admin_responded_at',
                'status',
                'resolved_by',
                'resolved_at'
            ]);
        });
    }
};
