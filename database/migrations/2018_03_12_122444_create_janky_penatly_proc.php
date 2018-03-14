<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJankyPenatlyProc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('janky_penatly_proc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('system_id');
            $table->integer('min_proc');
            $table->integer('max_proc');
            $table->integer('cost');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('janky_penatly_proc');
    }
}
