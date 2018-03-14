<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUserTestsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tests', function (Blueprint $table) {
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('checked_by')->references('id')->on('users');
            $table->foreign('template_id')->references('id')->on('template_user_tests');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_tests', function (Blueprint $table) {
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['checked_by']);
            $table->dropForeign(['template_id']);
        });
    }
}
