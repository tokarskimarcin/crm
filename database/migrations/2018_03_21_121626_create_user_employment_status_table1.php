<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmploymentStatusTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_employment_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('pbx_id');
            $table->date('pbx_id_add_date');
            $table->date('pbx_id_remove_date');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_employment_status');
    }
}
