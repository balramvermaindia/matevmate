<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersNotificationsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_notifications_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('notification_setting_id');
            $table->boolean('value')->default(1)->nullable();
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
        Schema::drop('users_notifications_settings');
    }
}
