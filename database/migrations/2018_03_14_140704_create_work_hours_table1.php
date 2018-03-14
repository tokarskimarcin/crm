<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkHoursTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_hours', function (Blueprint $table) {;
            $table->increments('id');
            $table->integer('status');
            $table->time('click_start');
            $table->time('click_stop');
            $table->time('register_start');
            $table->time('register_stop');
            $table->time('accept_start');
            $table->time('accept_stop');
            $table->integer('accept_sec');
            $table->integer('success');
            $table->integer('id_user')->unsigned();
            $table->integer('id_manager')->unsigned();
            $table->date('date');
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
        Schema::dropIfExists('work_hours');
    }
}
