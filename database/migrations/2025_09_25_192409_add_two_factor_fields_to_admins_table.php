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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('two_factor_secret')->nullable()->after('password');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_secret');
            $table->json('two_factor_recovery_codes')->nullable()->after('two_factor_confirmed_at');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_recovery_codes');
            $table->string('last_login_ip')->nullable()->after('two_factor_enabled');
            $table->timestamp('last_login_at')->nullable()->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_confirmed_at', 
                'two_factor_recovery_codes',
                'two_factor_enabled',
                'last_login_ip',
                'last_login_at'
            ]);
        });
    }
};
