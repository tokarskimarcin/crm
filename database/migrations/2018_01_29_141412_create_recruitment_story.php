<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentStory extends Migration
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
            $table->integer('candidate_id'); // FK
            $table->integer('cadre_id'); // FK
            $table->integer('cadre_edit_id')->nulablle(); // FK
            $table->integer('recruitment_attempt_id'); // FK
            $table->integer('attempt_status_id'); // FK
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
