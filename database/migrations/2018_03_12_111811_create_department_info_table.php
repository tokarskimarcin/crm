<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_dep')->unsigned();
            $table->integer('id_dep_type')->unsigned();
            $table->integer('size');
            $table->double('commission_avg');
            $table->integer('commission_hour');
            $table->integer('commission_start_money');
            $table->double('commission_step');
            $table->integer('dep_aim');
            $table->integer('dep_aim_week');
            $table->integer('commission_janky');
            $table->string('type', 100);
            $table->integer('janky_system_id')->unsigned();
            $table->integer('blocked');
            $table->integer('pbx_id');
            $table->integer('working_hours_normal');
            $table->integer('working_hours_week');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_info');
    }
}
