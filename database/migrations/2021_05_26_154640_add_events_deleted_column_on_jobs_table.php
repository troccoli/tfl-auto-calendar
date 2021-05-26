<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventsDeletedColumnOnJobsTable extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('total_events_to_delete')->default(0);
            $table->unsignedInteger('events_deleted')->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('total_events_to_delete');
            $table->dropColumn('events_deleted');
        });
    }
}
