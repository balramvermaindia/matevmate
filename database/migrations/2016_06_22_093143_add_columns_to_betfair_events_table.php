<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBetfairEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betfair_events', function (Blueprint $table) {
            $table->integer('betfair_sports_id')->after('id');
            $table->integer('betfair_competition_id')->after('betfair_sports_id');
            $table->integer('betfair_id')->after('betfair_competition_id');
            $table->integer('team1_id')->nullable()->after('team1');
            $table->integer('team2_id')->nullable()->after('team2');
            $table->boolean('result_declared')->default(0)->after('status');
            $table->integer('winner_id')->after('result_declared');
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
            $table->dropColumn('betfair_sports_id');
            $table->dropColumn('betfair_competition_id');
            $table->dropColumn('betfair_id');
            $table->dropColumn('team1_id');
            $table->dropColumn('team2_id');
            $table->dropColumn('result_declared');
            $table->dropColumn('winner_id');
        });
    }
}
