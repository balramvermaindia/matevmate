<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromBetfairEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betfair_events', function (Blueprint $table) {
            $table->dropColumn('sports_id');
            $table->dropColumn('event_id');
            $table->dropColumn('startdatetime');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('betfair_events', function (Blueprint $table) {
            //
        });
    }
}
