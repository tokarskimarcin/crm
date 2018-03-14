<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAcceptedPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accepted_payments', function (Blueprint $table) {
            $table->foreign('cadre_id')->references('id')->on('users');
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
        Schema::table('accepted_payments', function (Blueprint $table) {
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['department_info_id']);
        });
    }
}
