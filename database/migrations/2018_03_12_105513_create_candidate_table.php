<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_source_id')->unsigned();
            $table->integer('cadre_id')->unsigned();
            $table->integer('cadre_edit_id')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->integer('phone');
            $table->string('first_name', 191);
            $table->string('last_name', 191);
            $table->text('comment');
            $table->integer('attempt_status_id')->unsigned();
            $table->integer('training_stage');
            $table->integer('id_user')->unsigned();
            $table->integer('experience');
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
        Schema::dropIfExists('candidate');
    }
}
