<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourReportTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_info_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->time('hour');
            $table->date('report_date');
            $table->integer('is_send');
            $table->decimal('average', 10, 2);
            $table->integer('success');
            $table->integer('employee_count');
            $table->decimal('janky_count', 10,2);
            $table->decimal('wear_base', 10,2);
            $table->decimal('call_time', 10,2);
            $table->time('login_time');
            $table->decimal('hour_time_use', 10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hour_report');
    }
}
