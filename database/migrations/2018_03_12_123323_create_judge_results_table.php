<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJudgeResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('judge_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('it_id')->unsigned();
            $table->integer('notification_id')->unsigned();
            $table->integer('repaired');
            $table->integer('judge_quality');
            $table->integer('judge_contact');
            $table->integer('judge_time');
            $table->string('judge_sum', 11);
            $table->integer('response_after');
            $table->text('comment');
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
        Schema::dropIfExists('judge_results');
    }
}
