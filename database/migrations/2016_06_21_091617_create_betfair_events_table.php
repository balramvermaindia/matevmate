<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetfairEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betfair_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sports_id');
            $table->integer('event_id');
            $table->string('name')->nullable();
            $table->string('team1')->nullable();
            $table->string('team2')->nullable();
            $table->dateTime('startdatetime')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('betfair_events');
    }
}
