<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id');
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->integer('pesel')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->string('street');
            $table->integer('house_number');
            $table->integer('flat_number')->nullable();
            $table->string('package_name');
            $table->string('package_variable');
            $table->string('package_scope');
            $table->integer('phone_number');
            $table->string('scan_path');
            $table->integer('cadre_id');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted');
            $table->timestamps();
        });

        Schema::table('medical_packages', function (Blueprint $table) {
            $table->integer('user_id');

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_packages');
    }
}
