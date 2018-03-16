<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPrivilageUserRelationTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privilage_user_relation', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('link_id')->references('id')->on('links');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privilage_user_relation', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['link_id']);
        });
    }
}
