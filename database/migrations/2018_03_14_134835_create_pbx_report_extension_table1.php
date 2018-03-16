<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePbxReportExtensionTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbx_report_extension', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pbx_id');
            $table->time('login_time');
            $table->integer('count_private_pause');
            $table->integer('count_lesson_pause');
            $table->integer('received_calls');
            $table->integer('success');
            $table->float('average');
            $table->float('base_use_proc');
            $table->float('call_time_proc');
            $table->integer('time_pause');
            $table->float('dkj_proc');
            $table->date('report_date');
            $table->time('report_hour');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pbx_report_extension');
    }
}
