<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentAgencyStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_agency_story', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('consultant_id')->unsigned();
            $table->integer('cadre_id')->unsigned();
            $table->integer('agency_id')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->date('accept_month');
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
        Schema::dropIfExists('payment_agency_story');
    }
}
