<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Http\Requests;
use App\Challenges;
use App\Banter;
use App\FavoriteSports;
use App\FavoriteTeams;
use App\BetfairEvents;
use App\UsersMates;

class BanterController extends Controller
{
    public function load( Request $request )
    {
		$input 					= $request->input();
		$filter_type			= (isset($input['wo_wager_filter_type']) && !empty($input['wo_wager_filter_type'])) ? $input['wo_wager_filter_type'] : "mate";
		$mate_id 				= (isset($input['mate_id']) && !empty($input['mate_id'])) ? $input['mate_id'] : 0;
		$event_id 				= (isset($input['event_id']) && !empty($input['event_id'])) ? $input['event_id'] : 0;
		$event_selector			= (isset($input['event_selector']) && !empty($input['event_selector'])) ? $input['event_selector'] : "false";
		$check_last_wo			= (isset($input['check_last_wager_only']) && !empty($input['check_last_wager_only'])) ? $input['check_last_wager_only'] : "false";
		$last_id 				= (isset($input['last_id']) && !empty($input['last_id'])) ? $input['last_id'] : 0;
		$sync_msg_wo			= (isset($input['sync_msg_wo']) && !empty($input['sync_msg_wo'])) ? $input['sync_msg_wo'] : "false";
		$event_changer			= (isset($input['event_changer']) && !empty($input['event_changer'])) ? $input['event_changer'] : "false";
		$game_event_display		= (isset($input['game_event_display']) && !empty($input['game_event_display'])) ? $input['game_event_display'] : "false";
		
		$banter					= (isset($input['banter']) && !empty($input['banter'])) ? $input['banter'] : "both";
		
		$passedEvent			= (isset($input['e']) && !empty($input['e'])) ? $input['e'] : 0;
		$passedMate				= (isset($input['m']) && !empty($input['m'])) ? $input['m'] : 0;
		
		$wagers_events			= array();
		$wagers_events_array	= array();
		$wager_banter_discussion= array();
		$all_mates				= array();
		
		
		$pref_event_id 			= (isset($input['pref_event_id']) && !empty($input['pref_event_id'])) ? $input['pref_event_id'] : 0;
		$sync_msg_ps			= (isset($input['sync_msg_ps']) && !empty($input['sync_msg_ps'])) ? $input['sync_msg_ps'] : "false";
		$check_last_ps			= (isset($input['check_last_pref_wager_only']) && !empty($input['check_last_pref_wager_only'])) ? $input['check_last_pref_wager_only'] : "false";
		$sync_msg_ps			= (isset($input['sync_msg_ps']) && !empty($input['sync_msg_ps'])) ? $input['sync_msg_ps'] : "false";
		$pref_filter_type		= (isset($input['ps_wager_filter_type']) && !empty($input['ps_wager_filter_type'])) ? $input['ps_wager_filter_type'] : "game";
		
		$preferred_sports_data	= array();
		$pref_banter_discussion = array();
		
		$user_id 				= Auth::user()->id;
		$challange_id			= 0;
		$wagers_mate			= $this->get_wagers_by_user($user_id);

		$preferred_sports_data	= $this->get_preferred_sports_teams($user_id);
		
		if ( count($wagers_mate) && ($banter == 'left' || $banter == 'both') ) {
			if ( $filter_type == "mate" ) {
				$all_mates				= $this->get_mates_from_wagers($wagers_mate, $user_id);
				
				if ( $mate_id == 0 && count($all_mates) > 0 ) {
					$last_mate 	= reset($all_mates);
					$mate_id	= $last_mate->id;
					
					if ( $passedMate > 0 && $passedEvent > 0 ) {
						if(isset($all_mates[$passedMate])){
							$mate_id	= $passedMate;
						}
					}
				}
				
				if ( $mate_id ) {
					$wagers_events	= $this->get_event_by_mates_and_user($user_id, $mate_id);
					
					if ( count($wagers_events) > 0 ) {
						foreach ( $wagers_events as $wager ) {
							if ( isset($wager->event) ) {
								$wagers_events_array[$wager->event->id] = $wager->event;
							}
							
							if ($event_id != 0) {
								if ( $event_id == $wager->event->id ) {
									$challange_id =	$wager->id;
								}
							}
						}
						if ( $event_id == 0 ) {
							if ( count($wagers_events_array) > 0 ) {
								$last_event 	= reset($wagers_events_array);
								$event_id		= $last_event->id;
								
								if ( $passedMate > 0 && $passedEvent > 0 ) {
									if(isset($wagers_events_array[$passedEvent])){
										$event_id	= $passedEvent;
									}
								}
							}
						}
						foreach ( $wagers_events as $wager ) {
							if ( isset($wager->event) ) {
								if($event_id == $wager->event->id){
									$challange_id =	$wager->id;
								}
							}
						}
						if ( $check_last_wo == 'true' ) { // Sync (Wager Only Banter) for new message
							$wager_banter_discussion	= $this->get_discussion_by_event($event_id, $user_id, $mate_id, $last_id, $challange_id);
						} else {
							$wager_banter_discussion	= $this->get_discussion_by_event($event_id, $user_id, $mate_id, 0, $challange_id);
						}
					}
				}
			} else if( $filter_type == "game" ) {
				if ( $event_id == 0 ) {
					$event_id					= isset($wagers_mate[0]->event) ? $wagers_mate[0]->event->id : 0;
				}

				$wagers_events_array			= $this->get_events_from_wagers($wagers_mate);
				if ( $check_last_wo == 'true' ) { // Sync (Wager Only Banter) for new message
					$wager_banter_discussion	= $this->get_discussion_by_event($event_id, 0, 0, $last_id, 0);
				} else {
					$wager_banter_discussion	= $this->get_discussion_by_event($event_id);
				}
			}
		}

		if( count($preferred_sports_data) && ($banter == 'right' || $banter == 'both') ) { // Preferred Sports Data
			
			if ( $pref_event_id == 0 ) {
				$pref_event_id 	= $preferred_sports_data[0]->betfair_event_id;
			}
			
			if ( $check_last_ps == 'true' ) { // Sync (Preferred Sports Banter) for new message
				$pref_banter_discussion	= $this->get_discussion_by_event($pref_event_id, 0, 0, $last_id, 0);
			} else {
				$pref_banter_discussion	= $this->get_discussion_by_event($pref_event_id);
			}
			
		}
		
		if( $request->ajax() ) {
			if ( $banter == 'left' ) {
				if ( $sync_msg_wo == 'true' ) {
					if ( count($wager_banter_discussion) == 0 ) {
						return 'false';
						exit();
					}
				}
				return view('banter.banter_wager_ajax', compact('wager_banter_discussion', 'wagers_events_array', 'event_selector', 'sync_msg_wo', 'game_event_display'))->render();
			} else if ( $banter == 'right' ) {
				if ( $sync_msg_ps == 'true' ) {
					if ( count($pref_banter_discussion) == 0 ) {
						return 'false';
						exit();
					} 
				}
				return view('banter.pref_banter_wager_ajax', compact('pref_banter_discussion', 'preferred_sports_data', 'sync_msg_ps'))->render();
			}
		}
		
		return view('banter.index', compact('wager_banter_discussion', 'all_mates', 'wagers_events_array', 'preferred_sports_data', 'pref_banter_discussion', 'pref_event_id', 'passedEvent', 'passedMate'));
    }
	
	public function get_preferred_sports_teams($user_id)
	{
		$pref_sports_array 			= array();
		$pref_teams_array 			= array();
		$betfair_events 			= array();
		$betfair_events_array 		= array();
		$preferred_selection_data 	= array();
		
		$pref_sports 				= FavoriteSports::with('sport')->where('user_id', $user_id)->pluck('sport_id');

		if( count($pref_sports) ) {
			foreach ( $pref_sports as $sport ) {
				$pref_sports_array[] = $sport;
			}
			$pref_sports_ids = implode($pref_sports_array, "','");
		} else {
			$pref_sports_ids = false;
		}
			
		$pref_teams 				= FavoriteTeams::where('user_id', $user_id)->pluck('name');

		if( count($pref_teams) ) {
			foreach ( $pref_teams as $team ) {
				$pref_teams_array[] = $team;
			}
			$pref_teams_names = implode($pref_teams_array, "','");
		} else {
			$pref_teams_names = false;
		}

		$betfair_events = BetfairEvents::whereRaw("startdatetime >= '".date("Y-m-d H:i:s")."'
							and ( team1 in ('$pref_teams_names') or team2 in ('$pref_teams_names')
							or betfair_sports_id in ('$pref_sports_ids'))")
							->get();

		foreach ( $betfair_events as $eve ) {
			if ( !in_array($eve->id, $betfair_events_array) ) {
				$betfair_events_array[] = $eve->id;
			}
		}

		$preferred_selection_data = DB::table('betfair_events')
							->select('challenges.id as challenge_id', 'startdatetime', 'name', 'timezone', 'betfair_events.id as betfair_event_id')
							->leftJoin('challenges', 'challenges.betfair_event_id', '=', 'betfair_events.id') 
							->whereIn('betfair_events.id', $betfair_events_array)->groupBy('betfair_events.id')->get();
		
		return $preferred_selection_data;
	}
	
	public function get_wagers_by_user($user_id)
	{
		$wagers_mate	= Challenges::with('mate', 'user', 'event')->where(function($q) use ($user_id) {
								$q->where('user_id', $user_id)
								->orWhere('mate_id', $user_id);
						  })->where(function($q) use ($user_id) {
								$q->where('challenge_status','pending')
								->orWhere('challenge_status', 'active');
						  })->get();
						
		return $wagers_mate;
	}
	
	public function get_mates_from_wagers($wagers_mate, $user_id)
	{
		$all_mates	= array();
		if ( count($wagers_mate) > 0 ) {
			foreach ($wagers_mate as $mates) {
				if ( isset($mates->mate) ) {
					if ( $mates->mate->id == $user_id ) {
						$all_mates[$mates->user->id]	= $mates->user;
					} else {
						$all_mates[$mates->mate->id]	= $mates->mate;
					}
				}
			}
		}
		return $all_mates;
	}
	
	public function get_event_by_mates_and_user($user_id, $mate_id)
	{
		$mate_events	=	Challenges::with('event')->where(function($q) use ($user_id, $mate_id) {
								$q->where(function($query) use($user_id, $mate_id){
									$query->where('user_id', $user_id)
									->where('mate_id', $mate_id);
								})->orWhere(function($query) use($user_id, $mate_id){
									$query->where('user_id', $mate_id)
									->where('mate_id', $user_id);
								});
							})->Where(function($q){
								$q->where('challenge_status','pending')
								->orWhere('challenge_status', 'active');
							})->get();
		return $mate_events;
	}
	
	public function get_discussion_by_event($event_id, $user_id=0, $mate_id=0, $last_id=0, $challenge_id=0)
	{
		$banter_discussion_query	= Banter::with('commentBy')->where('is_deleted', 0);
		if ( $user_id != 0 && $mate_id != 0 ) {
			$banter_discussion_query	= $banter_discussion_query->where(function($q) use($user_id, $mate_id){
												$q->where('user_id', $user_id)
												->orWhere('user_id', $mate_id);
										  });
		}
		if ( $last_id !=0 ) {
			$banter_discussion_query	= $banter_discussion_query->where(function($q) use($last_id){
												$q->where('id', '>', $last_id);
										  });
		}
		if (  $challenge_id !=0 ) {
			$banter_discussion_query	= $banter_discussion_query->where('challenge_id', $challenge_id);
		}
		$banter_discussion	= $banter_discussion_query->where('event_id', $event_id)->get();
		//$this->printQuery();
		return $banter_discussion;
	}
	
	public function get_events_from_wagers($wagers_mate)
	{
		$events = array();
		
		foreach( $wagers_mate as $wager )
		{
			if ( isset($wager->event) ) {
				if ( !in_array( $wager->event, $events ) ) {
					array_push($events, $wager->event);
				} else {
					continue;
				}
			}
		}
		return $events;
	}
	
	
	public function postToBanter(Request $request)
	{
		$input 		= $request->input();
		$banterType	= $input['type'];
		$saved		= false;
		
		if ( isset($banterType) && $banterType == 'mywager' ) {
			$filterType					= $input['wo_wager_filter_type'];
			$mateID 					= $input['sel_mate_id'];
			$eventID 					= $input['sel_event_id'];
			$userID 					= Auth::user()->id;
			
			if ( $filterType == 'mate' ) {
				$selchallenge = $input['selchallenge'];
				if ( !$selchallenge ) {
					$challenge		= Challenges::where(function($q) use ($userID, $mateID) {
										$q->where(function($query) use($userID, $mateID){
											$query->where('user_id', $userID)
											->where('mate_id', $mateID);
										})->orWhere(function($query) use($userID, $mateID){
											$query->where('user_id', $mateID)
											->where('mate_id', $userID);
										});
									  })->Where(function($q){
										$q->where('challenge_status','pending')
										->orWhere('challenge_status', 'active');
									  })->where('betfair_event_id', $eventID)->first()->toArray();
									  
					$challengeID	= $challenge['id'];
				} else {
					$challengeID = $selchallenge;
				}
			} else if ( $filterType == 'game' ) {
				$challengeID			= 0;
			}
			$comment					= htmlspecialchars($input['users_comment'], ENT_COMPAT,'ISO-8859-1', true);
			
			$newBanter 					= new Banter();
			$newBanter->event_id		= $eventID;
			$newBanter->sport_id		= 0;
			$newBanter->user_id			= $userID;
			$newBanter->challenge_id	= $challengeID;
			$newBanter->comment			= $comment;
			$saved 						= $newBanter->save();
		}
		
		if ( isset($banterType) && $banterType == 'mysports' ) {
			$filterType					= $input['ps_wager_filter_type'];
			$mateID 					= $input['sel_mate_id'];
			$eventID 					= $input['sel_event_id'];
			$userID 					= Auth::user()->id;
			
			if ( $filterType == 'game' ) {
				$challengeID			= 0;
			}
			
			$comment					= htmlspecialchars($input['users_comment'], ENT_COMPAT,'ISO-8859-1', true);
			
			$newBanter 					= new Banter();
			$newBanter->event_id		= $eventID;
			$newBanter->sport_id		= 0;
			$newBanter->user_id			= $userID;
			$newBanter->challenge_id	= $challengeID;
			$newBanter->comment			= $comment;
			$saved 						= $newBanter->save();
		}
		
		if ( $saved ) {
			print 'true'; exit();
		} else {
			print 'false'; exit();
		}
		
	}
	
	public function deleteBanterComment(Request $request)
	{
		$user_ID  	= Auth::user()->id;
		$banter_ID	= $request->itemID;
		
		if ( $user_ID && $banter_ID ) {
			$update = [
				'is_deleted'    => 1,
			];
			$delete		= Banter::where('id', $banter_ID)->update($update);
			if ($delete) {
				print 'success';
			} else {
				print 'failed';
			}
		} else {
			print 'failed';
		}
	}
	
	public function getDeletedBanters(Request $request)
	{
		$input		= $request->input();
		$mate_id 	= (isset($input['mate_id']) && !empty($input['mate_id'])) ? $input['mate_id'] : 0;
		$event_id 	= (isset($input['event_id']) && !empty($input['event_id'])) ? $input['event_id'] : 0;
		$first_id 	= (isset($input['first_id']) && !empty($input['first_id'])) ? $input['first_id'] : 0;
		$last_id 	= (isset($input['last_id']) && !empty($input['last_id'])) ? $input['last_id'] : 0;
		$user_id  	= Auth::user()->id;
		
		if ( ($user_id > 0) && ($event_id > 0) && ($first_id > 0) && ($last_id > 0) ) {
			$deleted_banter_query = Banter::where('is_deleted', 1)->where('event_id', $event_id)->whereBetween('id', array(-$first_id, $last_id))->select('id')->get()->toArray();
			if ( count($deleted_banter_query) > 0 ) {
				$data = array();
				foreach ( $deleted_banter_query as $deleted_banter ) {
					$data[] = $deleted_banter['id'];
				}
				return $data;
			} else {
				print 'false';
			}
		} else {
			print 'false';
		}
	}
	
	private function checkBanterExists($banter_ID)
	{
		$banter = array();
		
		if ( $banter_ID ) {
			$banter = Banter::where('id', $banter_ID)->first();
		}
		return $banter;
	}
	
	public function loadRightBanter( Request $request )
	{
		$input 			= $request->input();
		$filter_type 	= (isset($input['filter_type']) && !empty($input['filter_type'])) ? $input['filter_type'] : "mates";
		$userMates		= array();
		$userID  		= Auth::user()->id;
		
		if ( $filter_type == 'mates' ) {
			$userMates	= UsersMates::with('mates')->where('user_id', $userID)->where('status', 'active')->get();
			
			if ( count($userMates) ) {
				$i = 0;
				foreach ( $userMates as $userMate ) {
					
					$matesWagers	= $this->get_wagers_by_user($userMate->mate_id);
					if (count($matesWagers) > 0) {
						$mateDissucionArray = array();
						foreach($matesWagers as $wager){
							$mateDissucion	= $this->get_discussion_by_event($wager->betfair_event_id);
							$winner_team	= $this->getWinnerByEvent($wager->betfair_event_id, $wager->team_id);
							
							array_push($mateDissucionArray, array('event'=> $wager, 'dissucion' => $mateDissucion, 'winner_team' => $winner_team));
						}
						$userMates[$i]->events = $mateDissucionArray;
					}
					$i++;
				}
				
			}


			return view('banter.right_banter_mates_ajax', compact('userMates'))->render();

		} else {
			return $userMates;
		}
		
		

	}
	
	public function getWinnerByEvent($betfair_event_id, $team_id){
		$betfairEvents	= BetfairEvents::where('id', $betfair_event_id)->first();
		if(count($betfairEvents) > 0){
			if($betfairEvents->team1_id == $team_id){
				return $betfairEvents->team1;
			}else{
				return $betfairEvents->team2;
			}
		}
	}

}
