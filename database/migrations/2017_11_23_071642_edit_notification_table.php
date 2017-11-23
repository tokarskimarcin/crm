<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('notifications', function (Blueprint $table) {
              $table->dateTime('data_start')->nullable();
              $table->dateTime('data_stop')->nullable();
              $table->time('sec')->nullable();
              $table->integer('priority');
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
