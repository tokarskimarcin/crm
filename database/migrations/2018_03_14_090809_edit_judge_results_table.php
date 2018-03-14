<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditJudgeResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('judge_results', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('it_id')->references('id')->on('users');
            $table->foreign('notification_id')->references('id')->on('notifications');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('judge_results', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['it_id']);
            $table->dropForeign(['notification_id']);
        });
    }
}
