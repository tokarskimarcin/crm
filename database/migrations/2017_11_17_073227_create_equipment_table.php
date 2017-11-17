<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('equipment_type_id');
            $table->string('laptop_processor')->nullable();
            $table->string('laptop_ram')->nullable();
            $table->string('laptop_hard_drive')->nullable();
            $table->integer('screnn_signal_cable')->nullable();
            $table->integer('phone_box')->nullable();
            $table->integer('tablet_modem')->nullable();
            $table->integer('sim_number_phone')->nullable();
            $table->integer('sim_id')->nullable();
            $table->integer('sim_type')->nullable();
            $table->integer('sim_pin')->nullable();
            $table->integer('sim_puk')->nullable();
            $table->integer('sim_net')->nullable();
            $table->string('model');
            $table->string('imei')->nullable();
            $table->string('serial_code');
            $table->string('description')->nullable();
            $table->integer('power_cable')->nullable();
            $table->integer('signal_cable')->nullable();
            $table->integer('status')->nullable();
            $table->integer('id_user')->unsigned()->nullable();
            $table->integer('id_manager')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('=equipment');
    }
}
