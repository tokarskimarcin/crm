<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentAttemptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_attempt', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_id')->unsigned();
            $table->integer('status');
            $table->integer('cadre_id')->unsigned();
            $table->integer('cadre_edit_id')->unsigned();
            $table->datetime('interview_date');
            $table->integer('interview_cadre')->unsigned();
            $table->integer('training_stage');
            $table->date('training_date');
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
        Schema::dropIfExists('recruitment_attempt');
    }
}
