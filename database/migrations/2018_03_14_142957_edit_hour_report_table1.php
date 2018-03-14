<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditHourReportTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hour_report', function (Blueprint $table) {
            $table->foreign('department_info_id')->references('id')->on('department_info');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hour_report', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_info_id']);
        });
    }
}
