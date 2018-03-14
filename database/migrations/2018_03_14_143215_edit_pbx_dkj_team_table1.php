<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPbxDkjTeamTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pbx_dkj_team', function (Blueprint $table) {
            $table->foreign('department_info_id')->references('id')->on('department_info');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pbx_dkj_team', function (Blueprint $table) {
            $table->dropForeign(['department_info_id']);
        });
    }
}
