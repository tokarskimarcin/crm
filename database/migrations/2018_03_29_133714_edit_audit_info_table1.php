<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAuditInfoTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_info', function (Blueprint $table) {
            $table->foreign('audit_criterion_id')->references('id')->on('audit_criterion');
            $table->foreign('audit_id')->references('id')->on('audit');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_info', function (Blueprint $table) {
            $table->dropForeign(['audit_criterion_id']);
            $table->dropForeign(['audit_id']);
        });
    }
}
