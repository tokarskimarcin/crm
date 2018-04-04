<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditAuditFilesTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_files', function (Blueprint $table) {
            $table->foreign('audit_id')->references('id')->on('audit');
            $table->foreign('criterion_id')->references('id')->on('audit_criterion');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_files', function (Blueprint $table) {
            $table->dropForeign(['audit_id']);
            $table->dropForeign(['criterion_id']);
        });
    }
}
