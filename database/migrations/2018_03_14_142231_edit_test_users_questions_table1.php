<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTestUsersQuestionsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_users_questions', function (Blueprint $table) {
            $table->foreign('test_question_id')->references('id')->on('test_questions');
            $table->foreign('user_question_id')->references('id')->on('user_questions');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_users_questions', function (Blueprint $table) {
            $table->dropForeign(['test_question_id']);
            $table->dropForeign(['user_question_id']);
        });
    }
}
