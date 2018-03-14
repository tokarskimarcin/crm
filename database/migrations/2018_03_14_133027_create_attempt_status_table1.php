<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttemptStatusTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempt_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->integer('status');
            $table->integer('attempt_order');
            $table->integer('default_attempt_result_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attempt_status');
    }
}
