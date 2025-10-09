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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // User identification (polymorphic to handle users, admins, collectors)
            $table->string('actor_type')->nullable(); // User, Admin, Collector
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_email')->nullable();
            
            // Activity details
            $table->string('event_type', 50); // login, logout, create, update, delete, view, etc.
            $table->string('activity_category', 30); // authentication, report_management, user_management, security, etc.
            $table->string('activity_description');
            $table->string('subject_type')->nullable(); // Model class name (Report, User, etc.)
            $table->unsignedBigInteger('subject_id')->nullable(); // ID of the subject
            
            // Request details
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->json('request_data')->nullable(); // Sanitized request data
            
            // Security information
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            
            // Data changes (for audit trail)
            $table->json('old_values')->nullable(); // Before values
            $table->json('new_values')->nullable(); // After values
            
            // Additional context
            $table->string('status', 20)->default('success'); // success, failed, error
            $table->string('severity', 20)->default('low'); // low, medium, high, critical
            $table->text('notes')->nullable();
            $table->json('additional_data')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['actor_type', 'actor_id']);
            $table->index(['event_type']);
            $table->index(['activity_category']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['ip_address']);
            $table->index(['created_at']);
            $table->index(['severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
