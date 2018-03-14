<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummaryPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_info_id')->unsigned();
            $table->string('month', 200);
            $table->integer('payment');
            $table->integer('hours');
            $table->integer('documents');
            $table->integer('students');
            $table->integer('employee_count');
            $table->timestamps();
            $table->integer('id_user')->unsigned();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summary_payment');
    }
}
