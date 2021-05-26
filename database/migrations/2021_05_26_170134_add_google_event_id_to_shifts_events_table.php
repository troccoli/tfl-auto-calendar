<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleEventIdToShiftsEventsTable extends Migration
{
    public function up(): void
    {
        Schema::table('shift_events', function (Blueprint $table) {
            $table->string('google_id')->after('is_all_day')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shift_events', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
    }
}
