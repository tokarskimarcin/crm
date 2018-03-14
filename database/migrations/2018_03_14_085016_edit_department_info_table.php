<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDepartmentInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('department_info', function (Blueprint $table) {
            $table->foreign('id_dep')->references('id')->on('departments');
            $table->foreign('id_dep_type')->references('id')->on('department_type');
            $table->foreign('janky_system_id')->references('id')->on('janky_penatly_proc');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_info', function (Blueprint $table) {
            $table->dropForeign(['id_dep']);
            $table->dropForeign(['id_dep_type']);
            $table->dropForeign(['janky_system_id']);
        });
    }
}
