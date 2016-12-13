<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSportsSysnameToBetfairSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betfair_sports', function (Blueprint $table) {
            $table->string('sports_sysname')->nullable()->after('sports_class');
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
             $table->dropColumn('sports_sysname');
        });
    }
}
