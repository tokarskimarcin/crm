<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->integer('id_manager')->unsigned();
            $table->integer('year');
            $table->integer('week_num');
            $table->string('monday_comment', 50);
            $table->time('monday_start');
            $table->time('monday_stop');
            $table->string('tuesday_comment', 50);
            $table->time('tuesday_start');
            $table->time('tuesday_stop');
            $table->string('wednesday_comment', 50);
            $table->time('wednesday_start');
            $table->time('wednesday_stop');
            $table->string('thursday_comment', 50);
            $table->time('thursday_start');
            $table->time('thursday_stop');
            $table->string('friday_comment', 50);
            $table->time('friday_start');
            $table->time('friday_stop');
            $table->string('saturday_comment', 50);
            $table->time('saturday_start');
            $table->time('saturday_stop');
            $table->string('sunday_comment', 50);
            $table->time('sunday_start');
            $table->time('sunday_stop');
            $table->timestamps();
            $table->integer('id_manager_edit')->unsigned();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule');
    }
}
