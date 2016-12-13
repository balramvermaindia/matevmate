<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetfairCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betfair_competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('betfair_sports_id');
            $table->integer('betfair_id');
            $table->string('competition_region')->nullable();
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
        Schema::drop('betfair_competitions');
    }
}
