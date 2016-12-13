<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersMatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_mates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('mate_id');
            $table->enum('status', ['active', 'deleted', 'blocked']);
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
        Schema::drop('users_mates');
    }
}
