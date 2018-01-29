<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('user_questions', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('test_id');
//            $table->integer('question_id');
//            $table->integer('available_time');
//            $table->integer('answer_time')->nullable();
//            $table->text('user_answer')->nullable();
//            $table->text('cadre_comment')->nullable();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_questions');
    }
}
