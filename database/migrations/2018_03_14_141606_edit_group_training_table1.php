<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditGroupTrainingTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_training', function (Blueprint $table) {
            $table->foreign('leader_id')->references('id')->on('users');
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('edit_cadre_id')->references('id')->on('users');
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
        Schema::table('group_training', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['edit_cadre_id']);
            $table->dropForeign(['department_info_id']);
        });
    }
}
