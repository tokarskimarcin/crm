<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePbxDkjTeamTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbx_dkj_team', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_info_id')->unsigned();
            $table->time('hour');
            $table->date('report_date');
            $table->integer('consultant_without_check');
            $table->integer('online_consultant');
            $table->integer('success');
            $table->integer('count_all_check');
            $table->integer('count_good_check');
            $table->integer('count_bad_check');
            $table->integer('all_jaky_disagreement');
            $table->integer('good_jaky_disagreement');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pbx_dkj_team');
    }
}
