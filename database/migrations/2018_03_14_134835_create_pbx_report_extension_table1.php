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
            $table->integer('closed_arranged');
            $table->integer('closed_bilingual');
            $table->integer('away_contacts');
            $table->integer('succes');
            $table->integer('wrong_number');
            $table->time('avg_time_pause');
            $table->double('avg_time_wait_per_hour',10,2);
            $table->time('avg_time_wait');
            $table->decimal('avg_succes_per_hour',10,2);
            $table->double('use_working_time',10,2);
            $table->time('avg_decision_time');
            $table->time('avg_delayed_time');
            $table->timestamp('report_date');
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
