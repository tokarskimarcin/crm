<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
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
            $table->integer('equipment_type_id')->unsigned();
            $table->string('laptop_processor', 191);
            $table->string('laptop_ram', 191);
            $table->string('laptop_hard_drive', 191);
            $table->integer('phone_box');
            $table->integer('tablet_modem');
            $table->string('imei', 191);
            $table->integer('sim_number_phone');
            $table->integer('sim_type');
            $table->integer('sim_id');
            $table->string('sim_pin', 100);
            $table->string('sim_puk', 100);
            $table->integer('sim_net');
            $table->string('model', 191);
            $table->string('serial_code', 191);
            $table->string('description', 191);
            $table->integer('power_cable');
            $table->integer('signal_cable');
            $table->integer('status');
            $table->integer('id_user')->unsigned();
            $table->integer('id_manager')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->timestamps();
            $table->datetime('to_user');
            $table->integer('deleted');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}
