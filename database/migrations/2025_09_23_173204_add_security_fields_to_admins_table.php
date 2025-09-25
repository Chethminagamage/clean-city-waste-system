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
            // Only add columns if they don't exist
            if (!Schema::hasColumn('admins', 'failed_login_attempts')) {
                $table->integer('failed_login_attempts')->default(0)->after('password');
            }
            if (!Schema::hasColumn('admins', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['failed_login_attempts', 'locked_until']);
        });
    }
};
