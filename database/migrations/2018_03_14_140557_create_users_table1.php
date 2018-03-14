<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {;
            $table->increments('id');
            $table->string('username', 191);
            $table->unique('username');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email_off', 191);
            $table->unique('email_off');
            $table->string('password', 191);
            $table->string('remember_token', 100);
            $table->timestamps();
            $table->date('last_login');
            $table->date('password_date');
            $table->integer('user_type_id')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->integer('dating_type');
            $table->date('start_work');
            $table->date('end_work');
            $table->integer('status_work');
            $table->integer('phone');
            $table->string('description', 255);
            $table->integer('student');
            $table->integer('salary_to_account');
            $table->integer('agency_id')->unsigned();
            $table->string('guid', 255);
            $table->string('login_phone', 255);
            $table->double('rate',10,2);
            $table->integer('id_manager')->unsigned();
            $table->integer('documents');
            $table->integer('salary');
            $table->integer('private_phone');
            $table->integer('candidate_id')->unsigned();
            $table->integer('additional_salary');
            $table->integer('main_department_id')->unsigned();
            $table->integer('coach_id')->unsigned();
            $table->integer('recommended_by');
            $table->date('promotion_date');
            $table->date('degradation_date');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
