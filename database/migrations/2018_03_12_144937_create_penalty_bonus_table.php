<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenaltyBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalty_bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->integer('id_user')->unsigned();
            $table->integer('amount');
            $table->string('comment',255);
            $table->integer('id_manager')->unsigned();
            $table->date('event_date');
            $table->timestamps();
            $table->integer('id_manager_edit')->unsigned();
            $table->integer('status');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penalty_bonus');
    }
}
