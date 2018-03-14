<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_story', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_id')->unsigned();
            $table->integer('cadre_id')->unsigned();
            $table->integer('cadre_edit_id')->unsigned();
            $table->integer('recruitment_attempt_id')->unsigned();
            $table->integer('attempt_status_id')->unsigned();
            $table->integer('attempt_result_id')->unsigned();
            $table->text('comment');
            $table->integer('last_attempt_status_id')->unsigned();
            $table->integer('last_attempt_result_id')->unsigned();
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
        Schema::dropIfExists('recruitment_story');
    }
}
