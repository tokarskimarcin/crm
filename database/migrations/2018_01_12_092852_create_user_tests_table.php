<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('user_tests', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('cadre_id');
//            $table->integer('user_id');
//            $table->integer('status')->default(0);
//            $table->integer('result')->nullable();
//            $table->integer('template_id')->default(0);
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tests');
    }
}
