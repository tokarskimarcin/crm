<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaching', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('consultant_id');
            $table->Integer('manager_id');
            $table->date('coaching_date');
            $table->string('subject');
            $table->string('comment');
            $table->decimal('average_goal_min', 10, 2);
            $table->decimal('average_goal_max', 10, 2);
            $table->Integer('status');
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
        Schema::dropIfExists('coaching');
    }
}
