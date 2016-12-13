<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use App\User;

use App\Challenges;

use App\FavoriteMatches;

use App\FavoriteSports;

use App\FavoriteTeams;

use App\BetfairEvents;

class DashboardController extends Controller
{
    public function index()
    {
		$user_id 					= Auth::user()->id;
		$currentDateTime 			= date('Y-m-d\TH:i:s\Z');
		$my_open_challenges			= array();
		$upcoming_sporting_events 	= array();
		$pref_sports_array 			= array();
		$pref_teams_array 			= array();
		$betfair_events_array 		= array();

		$my_open_challenges = Challenges::with('event','mate','user','product')->whereHas('event', function ($query) use($currentDateTime) {
							$query->where('startdatetime', '>=', $currentDateTime);	
						 })->Where(function ($query) use($user_id){
									$query->where('user_id',$user_id)->orWhere('mate_id',$user_id);
									})->whereIn('challenge_status', ['pending','active','awaiting'])->where('challenge_mode','mate')->orderBy('created_at','desc')->get();
		
		
		$pref_sports        = FavoriteSports::with('sport')->where('user_id', $user_id)->pluck('sport_id');
		
		if( count($pref_sports) ) {
			foreach ( $pref_sports as $sport ) {
				$pref_sports_array[] = $sport;
			}
			$pref_sports_ids = implode($pref_sports_array, "','");
		} else {
			$pref_sports_ids = false;
		}
		
		$pref_teams 	    = FavoriteTeams::where('user_id', $user_id)->pluck('name');

		if( count($pref_teams) ) {
			foreach ( $pref_teams as $team ) {
				$pref_teams_array[] = $team;
			}
			$pref_teams_names = implode($pref_teams_array, "','");
		} else {
			$pref_teams_names = false;
		}
		
		$betfair_events = BetfairEvents::whereRaw("startdatetime >= '".$currentDateTime."'
							and ( team1 in ('$pref_teams_names') or team2 in ('$pref_teams_names')
							or betfair_sports_id in ('$pref_sports_ids'))")
							->get();
		
		if( count($betfair_events) ){					
			foreach ( $betfair_events as $eve ) {
				if ( !in_array($eve->id, $betfair_events_array) ) {
					$betfair_events_array[] = $eve->id;
				}
			}
		}
		
		$pref_events		= FavoriteMatches::select('event_id')->where('user_id',$user_id)->get();
		
		if( count($pref_events) ){
			foreach ($pref_events as $pref_event ) {
				if ( !in_array($pref_event->event_id, $betfair_events_array) ) {
					$betfair_events_array[] = $pref_event->event_id;
				}
			}
		}
		
		$upcoming_sporting_events = BetfairEvents::whereIn('betfair_events.id', $betfair_events_array)->where('startdatetime', '>=', $currentDateTime)->where('running_status','pending')->where('status',1)->take(8)->orderBy('startdatetime', 'asc')->get();									
		return view('users.dashboard',compact('my_open_challenges','upcoming_sporting_events'));
	}
	
}
