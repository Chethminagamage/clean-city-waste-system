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
            $table->string('phone')->nullable()->after('email');
            $table->string('position')->nullable()->after('phone');
            $table->string('department')->nullable()->after('position');
            $table->string('profile_photo')->nullable()->after('department');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->json('notification_preferences')->nullable()->after('bio');
            $table->string('timezone')->default('Asia/Colombo')->after('notification_preferences');
            $table->boolean('two_factor_enabled')->default(false)->after('timezone');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_secret');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 
                'position', 
                'department', 
                'profile_photo', 
                'bio', 
                'notification_preferences', 
                'timezone', 
                'two_factor_enabled', 
                'two_factor_secret', 
                'last_login_at', 
                'last_login_ip', 
                'is_active'
            ]);
        });
    }
};
