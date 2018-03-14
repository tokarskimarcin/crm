<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcceptedPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accepted_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cadre_id')->unsigned();
            $table->date('payment_month');
            $table->integer('department_info_id')->unsigned();
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
        Schema::dropIfExists('accepted_payments');
    }
}
