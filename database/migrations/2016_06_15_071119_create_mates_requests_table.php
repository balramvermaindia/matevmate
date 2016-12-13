<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatesRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mates_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('mate_id');
            $table->enum('status', ['pending', 'accepted', 'rejected']);
            $table->dateTime('date_sent');
            $table->dateTime('date_accepted');
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
        Schema::drop('mates_requests');
    }
}
