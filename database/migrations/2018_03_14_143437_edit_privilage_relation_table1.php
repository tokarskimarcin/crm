<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPrivilageRelationTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privilage_relation', function (Blueprint $table) {
            $table->foreign('user_type_id')->references('id')->on('user_types');
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
        Schema::table('privilage_relation', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['link_id']);
        });
    }
}
