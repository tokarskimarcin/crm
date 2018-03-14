<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePbxTimeRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbx_time_record', function (Blueprint $table) {
            $table->increments('id');
            $table->string('team_name', 200);
            $table->text('campain');
            $table->string('consultant_name', 255);
            $table->time('time_on_record');
            $table->decimal('time_call',10,2);
            $table->time('hour');
            $table->date('report_date');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pbx_time_record');
    }
}
