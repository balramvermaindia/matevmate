<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetfairSportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betfair_sports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_type_id');
            $table->enum('event_type', ['sport', 'competition','market'])->default('sport');
            $table->string('event_name');
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
        Schema::drop('betfair_sports');
    }
}
