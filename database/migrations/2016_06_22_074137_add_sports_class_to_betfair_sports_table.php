<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSportsClassToBetfairSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('betfair_sports', function (Blueprint $table) {
            $table->string('sports_class')->nullable()->after('event_name');
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
            $table->dropColumn('sports_class');
        });
    }
}
