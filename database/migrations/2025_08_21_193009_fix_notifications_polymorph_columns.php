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
        // This migration is not needed since the notifiable columns and index
        // are already created in the create_notifications_table migration
        // through the $table->morphs('notifiable'); line.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
