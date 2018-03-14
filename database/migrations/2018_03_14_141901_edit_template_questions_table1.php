<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTemplateQuestionsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('template_questions', function (Blueprint $table) {
            $table->foreign('template_id')->references('id')->on('template_user_tests');
            $table->foreign('question_id')->references('id')->on('test_questions');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_questions', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropForeign(['question_id']);
        });
    }
}
