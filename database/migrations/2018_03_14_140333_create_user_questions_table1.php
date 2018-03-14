<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserQuestionsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_questions', function (Blueprint $table) {;
            $table->increments('id');
            $table->integer('test_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->integer('available_time');
            $table->integer('answer_time');
            $table->text('user_answer');
            $table->text('cadre_comment');
            $table->datetime('attempt');
            $table->integer('result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_questions');
    }
}
