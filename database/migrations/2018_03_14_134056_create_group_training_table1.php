<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTrainingTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_training', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leader_id')->unsigned();
            $table->integer('cadre_id')->unsigned();
            $table->integer('edit_cadre_id')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->text('comment');
            $table->integer('candidate_choise_count');
            $table->integer('candidate_avaible_count');
            $table->integer('candidate_absent_count');
            $table->date('training_date');
            $table->time('training_hour');
            $table->integer('status');
            $table->integer('training_stage');
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
        Schema::dropIfExists('group_training');
    }
}
