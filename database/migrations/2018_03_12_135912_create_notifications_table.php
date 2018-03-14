<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->text('content');
            $table->integer('user_id')->unsigned();
            $table->integer('notification_type_id')->unsigned();
            $table->integer('department_info_id')->unsigned();
            $table->integer('displayed_by');
            $table->datetime('data_start');
            $table->datetime('data_stop');
            $table->time('sec');
            $table->integer('status');
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
        Schema::dropIfExists('notifications');
    }
}
