<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditMedicalPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_packages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cadre_id')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_packages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['cadre_id']);
            $table->dropForeign(['updated_by']);
        });
    }
}
