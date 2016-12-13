<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEventTypeIdFromBetfairSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betfair_sports', function (Blueprint $table) {
            $table->dropColumn('event_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('betfair_sports', function (Blueprint $table) {
            //
        });
    }
}
