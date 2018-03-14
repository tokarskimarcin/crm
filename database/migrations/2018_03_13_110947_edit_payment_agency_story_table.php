<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPaymentAgencyStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_agency_story', function (Blueprint $table) {
            $table->foreign('consultant_id')->references('id')->on('users');
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('agency_id')->references('id')->on('agencies');
            $table->foreign('department_info_id')->references('id')->on('department_info');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_agency_story', function (Blueprint $table) {
            $table->dropForeign(['consultant_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['department_info_id']);
        });
    }
}
