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
            $table->enum('response_rating', ['helpful', 'not_helpful'])->nullable()->after('resolved_at');
            $table->timestamp('response_rated_at')->nullable()->after('response_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['response_rating', 'response_rated_at']);
        });
    }
};
