<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('banter', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id');
			$table->integer('sport_id');
			$table->integer('user_id');
			$table->integer('challenge_id');
			$table->text('comment');
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
        Schema::drop('banter');
    }
}
