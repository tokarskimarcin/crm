<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttemptResultAttemptStatusTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempt_result_attempt_status', function (Blueprint $table) {
            $table->integer('attempt_status_id')->unsigned();
            $table->integer('attempt_result_id')->unsigned();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attempt_result_attempt_status');
    }
}
