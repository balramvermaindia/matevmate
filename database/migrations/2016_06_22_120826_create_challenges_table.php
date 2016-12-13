<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('mate_id');
            $table->enum('challenge_status', ['pending', 'accepted','rejected'])->default('pending');
            $table->enum('challenge_mode', ['mate', 'banter'])->default('mate');
            $table->integer('betfair_event_id');
            $table->integer('team_id');
            $table->integer('product_id');
            $table->integer('product_quantity')->default(1);
            $table->enum('status', ['active', 'inactive','complete'])->default('active');
            $table->integer('winner_user_id')->nullable();
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
        Schema::drop('challenges');
    }
}
