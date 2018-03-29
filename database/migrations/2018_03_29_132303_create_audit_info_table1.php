<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditInfoTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('audit_criterion_id')->unsigned();
            $table->integer('audit_id')->unsigned();
            $table->boolean('status');
            $table->boolean('amount');
            $table->boolean('quality');
            $table->string('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_info');
    }
}
