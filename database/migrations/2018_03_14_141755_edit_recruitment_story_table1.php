<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditRecruitmentStoryTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_story', function (Blueprint $table) {
            $table->foreign('candidate_id')->references('id')->on('candidate');
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('cadre_edit_id')->references('id')->on('users');
            $table->foreign('recruitment_attempt_id')->references('id')->on('recruitment_attempt');
            $table->foreign('attempt_status_id')->references('id')->on('attempt_status');
            $table->foreign('attempt_result_id')->references('id')->on('attempt_result');
            $table->foreign('last_attempt_status_id')->references('id')->on('attempt_status');
            $table->foreign('last_attempt_result_id')->references('id')->on('attempt_result');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_story', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['cadre_edit_id']);
            $table->dropForeign(['recruitment_attempt_id']);
            $table->dropForeign(['attempt_status_id']);
            $table->dropForeign(['attempt_result_id']);
            $table->dropForeign(['last_attempt_status_id']);
            $table->dropForeign(['last_attempt_result_id']);
        });
    }
}
