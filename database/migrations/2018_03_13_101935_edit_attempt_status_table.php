<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAttemptStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attempt_status', function (Blueprint $table) {
            $table->foreign('default_attempt_result_id')->references('id')->on('attempt_result');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attempt_status', function (Blueprint $table) {
            $table->dropForeign(['default_attempt_result_id']);
        });
    }
}
