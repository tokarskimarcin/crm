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
        Schema::create('user_tests', function (Blueprint $table) {;
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('cadre_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('checked_by')->unsigned();
            $table->integer('status');
            $table->string('result', 10);
            $table->integer('template_id')->unsigned();
            $table->datetime('test_start');
            $table->datetime('test_stop');
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
        Schema::dropIfExists('user_tests');
    }
}
