<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate', function (Blueprint $table) {
            $table->foreign('candidate_source_id')->references('id')->on('candidate_source');
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('cadre_edit_id')->references('id')->on('users');
            $table->foreign('department_info_id')->references('id')->on('department_info');
            $table->foreign('attempt_status_id')->references('id')->on('attempt_status');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate', function (Blueprint $table) {
            $table->dropForeign(['candidate_source_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['cadre_edit_id']);
            $table->dropForeign(['department_info_id']);
            $table->dropForeign(['attempt_status_id']);
            $table->dropForeign(['id_user']);
        });
    }
}
