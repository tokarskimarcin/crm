<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditRecruitmentAttemptTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_attempt', function (Blueprint $table) {
            $table->foreign('candidate_id')->references('id')->on('candidate');
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('cadre_edit_id')->references('id')->on('users');
            $table->foreign('interview_cadre')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_attempt', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['cadre_edit_id']);
            $table->dropForeign(['interview_cadre']);
        });
    }
}
