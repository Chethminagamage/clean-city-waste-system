<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'auto' values to 'light'
        DB::table('users')->where('theme_preference', 'auto')->update(['theme_preference' => 'light']);
        
        // Then modify the enum to remove 'auto'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('theme_preference', ['light', 'dark', 'system'])->default('light')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'auto' back to the enum
        Schema::table('users', function (Blueprint $table) {
            $table->enum('theme_preference', ['light', 'dark', 'auto', 'system'])->default('light')->change();
        });
    }
};
