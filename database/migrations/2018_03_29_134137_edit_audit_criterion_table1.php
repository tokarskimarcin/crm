<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAuditCriterionTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_criterion', function (Blueprint $table) {
            $table->foreign('audit_header_id')->references('id')->on('audit_headers');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_criterion', function (Blueprint $table) {
            $table->dropForeign(['audit_header_id']);
        });
    }
}
