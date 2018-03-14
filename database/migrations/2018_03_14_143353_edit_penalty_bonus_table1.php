<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPenaltyBonusTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penalty_bonus', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_manager')->references('id')->on('users');
            $table->foreign('id_manager_edit')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penalty_bonus', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropForeign(['id_manager']);
            $table->dropForeign(['id_manager_edit']);
        });
    }
}
