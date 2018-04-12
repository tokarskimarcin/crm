<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAuditHeaderTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_header', function (Blueprint $table) {
            $table->foreign('status')->references('id')->on('audit_status');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_header', function (Blueprint $table) {
            $table->dropForeign(['status']);
        });
    }
}
