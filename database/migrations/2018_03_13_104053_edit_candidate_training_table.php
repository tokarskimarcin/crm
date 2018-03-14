<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCandidateTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_training', function (Blueprint $table) {
            $table->foreign('candidate_id')->references('id')->on('candidate');
            $table->foreign('training_id')->references('id')->on('group_training');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_training', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropForeign(['training_id']);
        });
    }
}
