<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChallengeStatusAndAcceptedStatusToChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->enum('challenge_status', ['won', 'lost', 'active','pending', 'awaiting','rejected'])->default('awaiting')->after('challenge_mode');
            $table->enum('accepted_status', ['pending', 'accepted','rejected'])->default('pending')->after('challenge_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn('challenge_status');
            $table->dropColumn('accepted_status');
        });
    }
}
