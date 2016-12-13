<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBetfairIdToBetfairSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('betfair_sports', function (Blueprint $table) {
            $table->integer('betfair_id')->after('id');
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
            $table->dropColumn('betfair_id');
        });
    }
}
