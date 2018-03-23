<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPbxReportExtensionTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pbx_report_extension', function (Blueprint $table) {
//            $table->foreign('pbx_id')->references('login_phone')->on('users');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pbx_report_extension', function (Blueprint $table) {
//            $table->dropForeign(['pbx_id']);
        });
    }
}
