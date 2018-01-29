<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('candidate_source_id'); // FK
            $table->integer('cadre_id'); // FK
            $table->integer('cadre_edit_id')->nulablle(); // FK
            $table->integer('department_info_id'); // FK
            $table->integer('phone'); // FK
            $table->string('first_name');
            $table->string('last_name');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
