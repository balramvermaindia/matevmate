<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\BetfairSports;

use App\BetfairEvents;

use App\BetfairCompetitions;

use App\FavoriteMatches;

use Auth;

use App\Challenges;

class SportsController extends Controller
{
	protected $passedMateID;
	public function __construct()
	{
		$this->passedMateID	= (isset($_GET['m']) && !empty($_GET['m'])) ? $_GET['m'] : 0;
		
	}
	
    public function getBetfairSports()
    {
		$passedMateID = $this->passedMateID;
		$sports = BetfairSports::where('status',1)->get();
		return view('users.sports.all_sports',compact('sports','passedMateID'));
	}
	
	public function getBetfairCompetitions($sports_sysname)
	{
		if ( $sports_sysname ) {	
			$passedMateID = $this->passedMateID;	
			$sport   = BetfairSports::where('sports_sysname', $sports_sysname)->where('status', 1)->first();
			if ( empty($sport) ) {
				return redirect('dashboard');
			}
			
			if ( $sport['event_type'] == 'competition' ) {
				$competition 		= array();
				$betfairSportsID 	= $sport['id'];
				$betfairID 			= $sport['betfair_id'];
				$competition 		= BetfairCompetitions::where('betfair_id', $betfairID)->where('betfair_sports_id', $betfairSportsID)->where('status',1)->first();
				
				if ( isset($passedMateID) && $passedMateID > 0 ) {
					$concatStr = '?m='.$passedMateID;
				} else {
					$concatStr = '';
				}
				return redirect('sports/competition/' . $competition['id'] . $concatStr);
			}
			
			$competitions   	= array();
			$currentDateTime 	= date('Y-m-d\TH:i:s\Z');
			$competitions  		= BetfairCompetitions::with('events')->whereHas('events', function ($query) use(						$currentDateTime) {
											$query->where('startdatetime', '>=', $currentDateTime)->where('status',1);	
												})->where('betfair_sports_id',$sport->id)->where('status',1)->get();
			$sport_name	 = $sport->event_name;
			return view('users.sports.betfair_competitions',compact('competitions','sport_name','passedMateID'));
		}else{
			return redirect('dashboard');
		}
	}
	
	public function getBetfairEvents(Request $request, $id)
	{
		if ( empty($id) ) {
			return redirect('dashboard');
		}
		$passedMateID = $this->passedMateID;
		
		
		if( isset($_GET['mateID']) ) {
			$passedMateID = base64_decode($_GET['mateID']);
		}
		
		$filter 				= $request->filter;
		$competition_id			= $id;
		$competition_name 		= BetfairCompetitions::where('id',$id)->where('status',1)->pluck('name')->first();
		$sport_id 				= BetfairCompetitions::where('id',$id)->where('status',1)->pluck('betfair_sports_id')->							 first();
		$sport		        	= BetfairSports::where('id', $sport_id)->where('status', 1)->first();
		$authID					= Auth::user()->id;
		$challenged_event_arr 	= array();
		if ( isset($passedMateID) && ( $passedMateID > 0 ) ) {
			
			
			$challenged_arr = Challenges::where('user_id',$authID)->where('mate_id', $passedMateID)->get();
			foreach( $challenged_arr as $arr ) {
				$challenged_event_arr[] = $arr['betfair_event_id'];
			}
		}
		
		$currentDateTime 	= date('Y-m-d\TH:i:s\Z');
		if( $filter ){
			$events 			= BetfairEvents::where('betfair_competition_id',$id)->where('status',1)->where('startdatetime','>=',$currentDateTime)->where('running_status','pending')->whereNotIn('id',$challenged_event_arr)->Where(function ($query) use(												$filter) {
														$query->where('team1', 'LIKE', '%'.$filter.'%')->orWhere('team2', 'LIKE', '%'.$filter.'%'); })->orderBy('startdatetime','asc')->get();
		}else{
			$events 			= BetfairEvents::where('betfair_competition_id',$id)->where('status',1)->where('startdatetime','>=',$currentDateTime)->where('running_status','pending')->whereNotIn('id',$challenged_event_arr)->orderBy('startdatetime','asc')->get();
		}
		return view('users.sports.betfair_events',compact('events','competition_name','competition_id','sport','passedMateID'));
	}
	
	public function getMatchDetails($id)
	{
		BetfairEvents::findOrFail($id);
		$team1 				= array();
		$team2				= array();
		$match 				= BetfairEvents::with('competition')->where('id',$id)->first();
		$favorite 			= FavoriteMatches::where('event_id',$id)->where('user_id',Auth::user()->id)->first();
		$team1_challenges 	= Challenges::where('team_id',$match->team1_id)->where('winner_user_id','<>',Null)->get();
		$team2_challenges 	= Challenges::where('team_id',$match->team2_id)->where('winner_user_id','<>',Null)->get();
		$team1_wagers_won 	= 0;
		$team1_wagers_lost 	= 0;
		$team2_wagers_won 	= 0;
		$team2_wagers_lost 	= 0;
		if( count( $team1_challenges ) ){
			foreach( $team1_challenges as $team1_challenge ){
				if( $team1_challenge->user_id == $team1_challenge->winner_user_id ){
					$team1_wagers_won++;
				}else{
					$team1_wagers_lost++;
				}
			}
		}
		$team1['wagers_won'] 	= $team1_wagers_won;
		$team1['wagers_lost'] 	= $team1_wagers_lost;
		$team1['total_wagers'] 	= $team1_wagers_won+$team1_wagers_lost;
		
		if( count( $team2_challenges ) ){
			foreach( $team2_challenges as $team2_challenge ){
				if( $team2_challenge->user_id == $team2_challenge->winner_user_id ){
					$team2_wagers_won++;
				}else{
					$team2_wagers_lost++;
				}
			}
		} 
		$team2['wagers_won'] 	= $team2_wagers_won;
		$team2['wagers_lost'] 	= $team2_wagers_lost;
		$team2['total_wagers']	= $team2_wagers_won+$team1_wagers_lost;
		
		return view('users.sports.match_details',compact('match','favorite','team1','team2'));
	}
}
