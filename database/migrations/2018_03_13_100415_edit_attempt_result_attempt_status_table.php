<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAttemptResultAttemptStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attempt_result_attempt_status', function (Blueprint $table) {
            $table->foreign('attempt_status_id')->references('id')->on('attempt_status');
            $table->foreign('attempt_result_id')->references('id')->on('attempt_result');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attempt_result_attempt_status', function (Blueprint $table) {
            $table->dropForeign(['attempt_status_id']);
            $table->dropForeign(['attempt_result_id']);
        });
    }
}
