<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUsersTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->foreign('department_info_id')->references('id')->on('department_info');
            $table->foreign('agency_id')->references('id')->on('agencies');
            $table->foreign('id_manager')->references('id')->on('users');
            $table->foreign('candidate_id')->references('id')->on('candidate');
            $table->foreign('main_department_id')->references('id')->on('department_info');
            $table->foreign('coach_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['department_info_id']);
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['id_manager']);
            $table->dropForeign(['candidate_id']);
            $table->dropForeign(['main_department_id']);
            $table->dropForeign(['coach_id']);
        });
    }
}
