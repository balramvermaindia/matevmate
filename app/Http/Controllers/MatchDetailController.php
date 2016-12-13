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

class MatchDetailController extends Controller
{
	
    public function doAddFavoriteMatch(Request $request)
    {
		$saved 							= 0;
		$eventID 						= $request->eventID;
		if( $eventID ) {
			$userID  					= Auth::user()->id;
			$FavoriteMatches 			= new FavoriteMatches();
			$FavoriteMatches->user_id 	= $userID;
			$FavoriteMatches->event_id	= $eventID;
			$save  						= $FavoriteMatches->save(); 
			if( $save )	{
				$saved = 1;
			} 	
		}	
		return $saved;
	}
	
	public function viewExistingWagers(Request $request)
	{
		$eventID 						= $request->eventID;
		if( $eventID ){
			$userID  					= Auth::user()->id;
			$my_open_challenges 			= Challenges::with('mate','user','event','event.sports','product')->Where(									function ( $query ) use($userID){
										  $query->where('user_id',$userID)
										  ->orWhere('mate_id',$userID);
										  })->where('betfair_event_id',$eventID)->get();
			return view('users.sports.view_existing_wagers_ajax',compact('my_open_challenges'))->render();
			
		} else {
			return 0;
		}
	}
}
