<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->string('waste_type')->nullable();
            $table->text('additional_details')->nullable();
        });
    }

    public function down()
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->dropColumn(['waste_type', 'additional_details']);
        });
    }
};
