<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname',50);
            $table->string('lastname',50);
            $table->string('email')->unique();
            $table->string('password',100);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('dob');
            $table->string('photo',100)->nullable();
            $table->string('phone',50)->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('country',100)->nullable();
            $table->string('zipcode',30)->nullable();
            $table->boolean('status')->default(0);
            $table->string('social_id',100)->nullable();;
            $table->enum('social_type', ['twitter', 'facebook','google'])->nullable();
            $table->string('user_auth_code',200)->nullable();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
