<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditEquipmentsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('equipment_type_id')->references('id')->on('equipment_types');
            $table->foreign('id_manager')->references('id')->on('users');
            $table->foreign('department_info_id')->references('id')->on('department_info');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropForeign(['equipment_type_id']);
            $table->dropForeign(['id_manager']);
            $table->dropForeign(['department_info_id']);
        });
    }
}
