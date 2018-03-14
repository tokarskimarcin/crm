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
            $table->integer('user_id')->unsigned();
            $table->string('user_first_name', 191);
            $table->string('user_last_name', 191);
            $table->string('pesel', 11);
            $table->string('birth_date', 191);
            $table->string('postal_code', 191);
            $table->string('city', 191);
            $table->string('street', 191);
            $table->integer('house_number');
            $table->integer('flat_number');
            $table->string('package_name', 191);
            $table->string('package_variable', 191);
            $table->string('package_scope', 191);
            $table->integer('phone_number');
            $table->string('scan_path', 191);
            $table->date('month_start');
            $table->date('month_stop');
            $table->integer('cadre_id')->unsigned();
            $table->integer('family_member');
            $table->integer('updated_by')->unsigned();
            $table->integer('deleted');
            $table->integer('hard_deleted');
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
        Schema::dropIfExists('medical_packages');
    }
}
