<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateUserTestsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_user_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_name',191);
            $table->string('name',191);
            $table->integer('cadre_id')->unsigned();
            $table->integer('deleted');
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
        Schema::dropIfExists('template_user_tests');
    }
}
