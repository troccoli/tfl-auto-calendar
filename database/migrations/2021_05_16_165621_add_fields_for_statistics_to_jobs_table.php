<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsForStatisticsToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('total_shifts')->default(0);
            $table->unsignedInteger('generated_shifts')->default(0);
            $table->unsignedInteger('total_events')->default(0);
            $table->unsignedInteger('events_sent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('total_shifts');
            $table->dropColumn('generated_shifts');
            $table->dropColumn('total_events');
            $table->dropColumn('events_sent');
        });
    }
}
