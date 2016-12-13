<?php

use Illuminate\Database\Seeder;

class NotificationSettings extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('notifications_settings')->insert([
            [
			   'sysname' => 'CHALLENGEAMATE',
			   'setting' => 'When you challenge a mate',
			],
            [
			   'sysname' => 'CHALLENGEACCEPTEDDECLINED',
			   'setting' => 'When a challenge has been accepted/declined',
			],
            [
			   'sysname' => 'WAGERHONOURED',
			   'setting' => 'When a wager is honoured',
			],
            [
			   'sysname' => 'HONOURAWAGER',
			   'setting' => 'When you need to honour a wager',
			],
            [
			   'sysname' => 'RESULTOFGAMESWITHWAGER',
			   'setting' => 'Telling you the results of a game you’ve placed a wager on',
			],
            [
			   'sysname' => 'MATEINVITATIONACCEPTED',
			   'setting' => 'When a mate accepts your invitation to join',
			],
            [
			   'sysname' => 'UPCOMINGFAVTEAMMATCH',
			   'setting' => 'When there is an upcoming match for your favourite team',
			],
            [
			   'sysname' => 'SOMEBODYCHALLENGEDYOU',
			   'setting' => 'When somebody challenges you',
			],
        ]);
    }
}
