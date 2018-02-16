<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttemptResultAttemptStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempt_result_status', function (Blueprint $table) {

            $table->integer('attempt_status_id')->unsigned();
            $table->foreign('attempt_status_id')->references('id')->on('attempt_status');

            $table->integer('attempt_result_id')->unsigned();
            $table->foreign('attempt_result_id')->references('id')->on('attempt_result');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
