<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDkjTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dkj', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('add_date');
            $table->integer('id_user')->unsigned();
            $table->integer('id_dkj')->unsigned();
            $table->integer('id_manager')->unsigned();
            $table->datetime('date_manager');
            $table->text('comment_manager');
            $table->integer('phone');
            $table->string('campaign', 255);
            $table->text('comment');
            $table->integer('dkj_status');
            $table->integer('manager_status');
            $table->integer('deleted');
            $table->integer('edit_dkj');
            $table->datetime('edit_date');
            $table->integer('department_info_id')->unsigned();
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dkj');
    }
}
