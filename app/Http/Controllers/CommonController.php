<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\User;
use App\MatesReqests;
use App\UsersMates;
use App\Notifications;
use Hash;
use Session;
use DB;
use Redirect;
use App\Challenges;
use App\BetfairEvents;
use App\BetfairSports;
use App\FavoriteTeams; 
use App\FavoriteSports;
use App\FavoriteMatches;

class CommonController extends Controller
{
	public function showUserProfile($userID)
	{
		if ( $userID ) {
			$profile             = User::where('status', 1)->where('id', $userID)->first();
			$my_open_challenges  = Challenges::with('event','mate','user','product')->Where(function ($query) use($userID){
								   $query->where('user_id',$userID)
								   ->orWhere('mate_id',$userID);
                                   })->whereIn('challenge_status', ['pending','active','awaiting'])->where('challenge_mode','mate')->get();
			
			if ( count($profile) ) {
				$authID			= Auth::user()->id;
				$mateRequest	= array();
				$userMates		= array();
				$status			= 'notAMate';
				$userMates		= UsersMates::where('user_id', $authID)->where('mate_id', $userID)->first(); // If this user is the mate of logged in user?
				
				if ( count($userMates) == 0 ) {
					$mateRequest = MatesReqests::where('user_id', $userID)->where('mate_id', $authID)->where('status', '!=', 'rejected')->where('status', '!=', 'accepted')->first(); // If this user sent me a request?
					if ( count($mateRequest) > 0 ) {
						if ( $mateRequest->status == 'pending' ) {
							$status = 'incomingRequestPending';
						}
					} else {
						$mateRequest = MatesReqests::where('user_id', $authID)->where('mate_id', $userID)->where('status', '!=', 'rejected')->where('status', '!=', 'accepted')->first(); // If I sent this user a request?
						if ( count($mateRequest) > 0 ) {
							if ( $mateRequest->status == 'pending' ) {
								$status = 'outgoingRequestPending';
							}
						}
					}
				}
					
				return view('users.profile.user-profile', compact('profile', 'userMates', 'status', 'mateRequest','my_open_challenges','userID'));
			} else {
				return redirect('/'); // maybe id was given explicitly
			}
			
		} else {
			return redirect('/');
		}
	}
	
	public function addNewMate($userID)
	{
		if ( $userID ) {
			$profile = User::where('status', 1)->where('id', $userID)->first();
			$authID	 = Auth::user()->id;
			if ( count($profile) ) {
				$checkRequestAlreadySent	= MatesReqests::where('user_id', $userID)->where('mate_id', $authID)->where('status', 'pending')->orderBy('date_sent', 'desc')->limit(1)->offset(0)->first();				
				
				if ( count($checkRequestAlreadySent) ) {
					return Redirect::to('/user-profile/' . $userID);
				}
				
				$newRequest 				= new MatesReqests();
				$newRequest->user_id 		= $authID;
				$newRequest->mate_id 		= $userID;
				$newRequest->status 		= 'pending';
				$newRequest->date_sent		= date('Y-m-d H:i:s');
				$newRequest->date_accepted 	= '0000-00-00 00:00:00';
				$saved						= $newRequest->save();
				
				if ( $saved ) {
					$html							= '';
					$authProfile					= User::where('status', 1)->where('id', $authID)->first();
					$senderName						= ucwords(@$authProfile->firstname . ' ' . @$authProfile->lastname);
					
					
					if ( isset($authProfile->photo) ) {
						$senderPhoto = url('assets/users/img/user_profile/'.$authProfile->photo);
					} else {
						$senderPhoto = url('assets/img/cmt.png');
					}

					//~ $html = '<div class="list-row notification">
//~ 
								//~ <div class="list-col">
									//~ <div class="small_profile">
										//~ <img src="'.$senderPhoto.'" style="height:auto;">
									//~ </div>
									//~ <span>'.@$senderName.' </span> sent you a mate request.
									//~ <p><i class="fa fa-calendar" aria-hidden="true"></i> '.date('d M, Y').'</p>
								//~ </div>
								//~ <div class="list-col cmts matchs_btn">
									//~ <a class="cmt_btn" target="_blank" href="'.url("user-profile/$authID").'">View Details</a>
								//~ </div>
							//~ </div>';
							
					$this->sendNotification($userID, 'addmate', $authID );
					$receiverName = ucwords(@$profile->firstname . ' ' . @$profile->lastname);
					
					$to      = $profile->email;
					$subject = "Mate Request - MateVMate";
					$message = '<html><body>';
					$message .= "Hello " . @$receiverName . ",<br><br>";
					$message .= @$senderName ." sent you a mate request. <br>";
					$message .= "Please login to your MateVMate account to see all pending invitations. <br><br><br><br>";
					$message .= "Thanks <br>";
					$message .= "MateVMate";
					$message .= "</body></html>";
					
					$this->sendEmail($to, $subject, $message); 
					
					Session::flash('success', 'Mate request sent successfully.');
				} else {
					Session::flash('error', 'There was some error while sending request. Please try again in some time.');
				}
				return Redirect::to('/user-profile/' . $userID);
			} else {
				return redirect('/');
			}
		} else {
			return redirect('/');
		}
	}
	
	public function acceptMateRequest($requestID)
	{
		if ( $requestID ) {
			$request	= MatesReqests::where('id', $requestID)->first();
			$userID		= $request->user_id;

			$profile 	= User::where('status', 1)->where('id', $userID)->first();
			
			$authID	 	= Auth::user()->id;
			if ( count($profile) ) {
				//$request				= MatesReqests::where('user_id', $userID)->where('mate_id', $authID)->orderBy('id', 'desc')->limit(1)->offset(0)->first();
				
				$delete                         = $request->delete();
				//~ $request->status 				= 'accepted';
				//~ $request->date_accepted			= date('Y-m-d H:i:s');
				//~ $requestSaved					= $request->save();
				
				$userMatesSender				= new UsersMates();
				$userMatesSender->user_id		= $userID;
				$userMatesSender->mate_id		= $authID;
				$userMatesSender->status		= 'active';
				$userMatesSenderSaved			= $userMatesSender->save();
				
				$userMatesReceiver				= new UsersMates();
				$userMatesReceiver->user_id		= $authID;
				$userMatesReceiver->mate_id		= $userID;
				$userMatesReceiver->status		= 'active';
				$userMatesReceiverSaved			= $userMatesReceiver->save();
				
				if ( $delete && $userMatesSenderSaved && $userMatesReceiverSaved ) {
					$html							= '';
					$authProfile					= User::where('status', 1)->where('id', $authID)->first();
					$senderName						= ucwords(@$authProfile->firstname . ' ' . @$authProfile->lastname);
					
					if ( isset($authProfile->photo) ) {
						$senderPhoto = url('assets/users/img/user_profile/'.$authProfile->photo);
					} else {
						$senderPhoto = url('assets/img/cmt.png');
					}
					$receiverName = ucwords(@$profile->firstname . ' ' . @$profile->lastname);
					
					
					$to      = $profile->email;
					$subject = "Mate Request Accepted - MateVMate";
					$message = '<html><body>';
					$message .= "Hello " . @$receiverName . ",<br><br>";
					$message .= @$senderName ." accepted your mate request. <br>";
					$message .= "You can now place a wager with this user. <br><br><br><br>";
					$message .= "Thanks <br>";
					$message .= "MateVMate";
					$message .= "</body></html>";
					

					$canWeSendNotification = $this->checkIfUserWantNotification($userID, 'MATEINVITATIONACCEPTED'); // Check what user want
					
					$this->sendNotification($userID, 'acceptmaterequest', $authID);
					
					if ( $canWeSendNotification ) {
						$this->sendEmail($to, $subject, $message);
					}
					
					
					Session::flash('success', 'Mate request accepted successfully.');
				} else {
					Session::flash('error', 'Some error occured while accepting request. Please try again in some time.');
				}
				//return Redirect::to('/user-profile/' . $userID);
				return Redirect::back();
			} else {
				return redirect('/');
			}
		} else {
			return redirect('/');
		}
	}
	
	public function rejectMateRequest($requestID)
	{
		if ( $requestID ) {
			$mateRequest= MatesReqests::where('id', $requestID)->first();
			$userID		= $mateRequest->user_id;
			
			$profile 	= User::where('status', 1)->where('id', $userID)->first();
			$authID	 	= Auth::user()->id;
			
			if ( count($profile) ) {
				$save 								= $mateRequest->delete();

				if ($save) {
					$html							= '';
					$authProfile					= User::where('status', 1)->where('id', $authID)->first();
					$senderName						= ucwords(@$authProfile->firstname . ' ' . @$authProfile->lastname);
					
					if ( isset($authProfile->photo) ) {
						$senderPhoto = url('assets/users/img/user_profile/'.$authProfile->photo);
					} else {
						$senderPhoto = url('assets/img/cmt.png');
					}

					//~ $html = '<div class="list-row notification">
								//~ <div class="small_profile">
									//~ <img src="'.$senderPhoto.'" style="height:auto;">
								//~ </div>
								//~ <div class="list-col">
									//~ <span>'.@$senderName.' </span> rejected your mate request.
									//~ <p><i class="fa fa-calendar" aria-hidden="true"></i> '.date('d M, Y').'</p>
								//~ </div>
							//~ </div>';
							
					$this->sendNotification($userID, 'rejectmaterequest', $authID );
					$receiverName = ucwords(@$profile->firstname . ' ' . @$profile->lastname);
					
					$to      = $profile->email;
					$subject = "Mate Request Rejected - MateVMate";
					$message = '<html><body>';
					$message .= "Hello " . @$receiverName . ",<br><br>";
					$message .= @$senderName ." rejected your mate request. <br>";
					$message .= "Please login to your MateVMate account to see all pending notifications. <br><br><br><br>";
					$message .= "Thanks <br>";
					$message .= "MateVMate";
					$message .= "</body></html>";
					
					$this->sendEmail($to, $subject, $message);
					
					Session::flash('success', 'Mate request rejected successfully.');
				} else {
					Session::flash('error', 'Some error occured while rejecting request. Please try again in some time.');
				}
				//return Redirect::to('/user-profile/' . $userID);
				return Redirect::back();
			} else {
				return redirect('/');
			}
		} else {
			return redirect('/');
		}
	}
	
	public function removeMate($userID)
	{
		if ( $userID ) {
			$profile = User::where('status', 1)->where('id', $userID)->first();
			$authID	 = Auth::user()->id;
			if ( count($profile) ) {
				$deleteFirst 	= UsersMates::where('user_id', $userID)->where('mate_id', $authID)->delete();
				$deleteSecond 	= UsersMates::where('user_id', $authID)->where('mate_id', $userID)->delete();
				
				if ( $deleteFirst && $deleteSecond ) {
					Session::flash('success', 'Mate removed successfully.');
				} else {
					Session::flash('error', 'Some error occured while removing mate. Please try again in some time.');
				}
				//return Redirect::to('/user-profile/' . $userID);
				return Redirect::back();
			} else {
				return redirect('/');
			}
			
		} else {
			return redirect('/');
		}
	}
	
	public function fetchUniqueTeams(Request $request)
	{
		$term					= $request->term; 
		$teams 					= array();
		$data					= array();
		$favorite_teams_array	= array();
		$userID					= Auth::user()->id;
		$favorite_teams 		= FavoriteTeams::select('name')->where('user_id',$userID)->get();
		
		foreach($favorite_teams as $favorite_team){
			$favorite_teams_array[] = $favorite_team->name;
		}
		
		
		if ( count($favorite_teams_array) ) {
			$first 		= BetfairEvents::select('team1 AS team')->where('team1','LIKE','%'.$term.'%')->whereNotIn('team1',$favorite_teams_array)->groupBy('team1');
			$teams 		= BetfairEvents::select('team2 As team')->where('team2','LIKE','%'.$term.'%')->whereNotIn('team2',$favorite_teams_array)->groupBy('team2')->union($first)->where('status',1)->get();
		} else {
			$first 		= BetfairEvents::select('team1 AS team')->where('team1','LIKE','%'.$term.'%')->groupBy('team1');
			$teams 		= BetfairEvents::select('team2 As team')->where('team2','LIKE','%'.$term.'%')->groupBy('team2')->union($first)->where('status',1)->get();
		}
		
		if( count($teams)>0 ) {
			foreach( $teams as $team ) {
				$data[] = $team->team; 
			}
		}
		return json_encode($data);
	}
	
	public function doAddFavoriteTeam(Request $request)
	{
		$team     					= $request->team;
		if($team){
			$user_id  					= Auth::user()->id;
			$FavoriteTeams 				= new FavoriteTeams();
			$FavoriteTeams->user_id 	= $user_id;
			$FavoriteTeams->name		= $team;
			$save  						= $FavoriteTeams->save();
			
			if($save){
				return '1';
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	
	public function doRemoveFavoriteTeam($id)
	{
		$id    		= $id;
		$user_id  	= Auth::user()->id;
		$delete		= FavoriteTeams::where('user_id', $user_id)->where('id', $id)->delete();
		
		if($delete){
			return back()->with('success','Team removed successfully');
		}else{
			return back()->with('error', 'An unknown error occured.');
		}
	}
	
	public function fetchUniqueSports(Request $request)
	{
		$term					= $request->term; 
		$sports					= array();
		$favorite_sports_array 	= array();
		$data					= array();
		$userID					= Auth::user()->id;
		$favorite_sports 		= FavoriteSports::select('sport_id')->where('user_id',$userID)->get();
		foreach($favorite_sports as $favorite_sport){
			$favorite_sports_array[] = $favorite_sport->sport_id;
		}
		
		if( count($favorite_sports_array) ){
			$sports 			= BetfairSports::select('event_name As sport')->where('event_name','LIKE','%'.$term.'%')->whereNotIn('id',$favorite_sports_array)->groupBy('event_name')->where('status',1)->get();
		}else{
			$sports 			= BetfairSports::select('event_name As sport')->where('event_name','LIKE','%'.$term.'%')->groupBy('event_name')->where('status',1)->get();
		}
		
		
		if( count($sports)>0 ) {
			foreach( $sports as $sport ) {
				$data[] = $sport->sport; 
			}
		}
		
		return json_encode($data);
	}
	
	public function doAddFavoriteSport(Request $request)
	{
		$sport     					= $request->sport;
		if( $sport ){
			$sport_id            		= BetfairSports::select('id')->where('event_name',$sport)->first();
			$user_id  					= Auth::user()->id;
			$FavoriteSports 			= new FavoriteSports();
			$FavoriteSports->user_id 	= $user_id;
			$FavoriteSports->sport_id	= $sport_id->id;
			$save  						= $FavoriteSports->save();
			
			if($save){
				return '1';
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	public function doAddFavoriteMatch(Request $request)
	{
		$match_id     					= $request->match;
		
		if( $match_id ){
			//~ $match_array 				= explode("on",$match);
			//~ $match_name					= $match_array[0];
			//~ $match_id            		= BetfairEvents::select('id')->where('name',$match_name)->first();
			$user_id  					= Auth::user()->id;
			$FavoriteMatches 			= new FavoriteMatches();
			$FavoriteMatches->user_id 	= $user_id;
			$FavoriteMatches->event_id	= $match_id;
			$save  						= $FavoriteMatches->save();
			
			if($save){
				return '1';
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	
	public function doRemoveFavoriteSport($id)
	{
		$id    		= $id;
		$user_id  	= Auth::user()->id;
		$delete		= FavoriteSports::where('user_id', $user_id)->where('id', $id)->delete();
		
		if($delete){
			return back()->with('success','Sport removed successfully');
		}else{
			return back()->with('error', 'An unknown error occured.');
		}
	}
	
	public function doRemoveFavoriteMatch($id)
	{
		$id    		= $id;
		$user_id  	= Auth::user()->id;
		$delete		= FavoriteMatches::where('user_id', $user_id)->where('id', $id)->delete();
		
		if($delete){
			return back()->with('success','Match removed successfully');
		}else{
			return back()->with('error', 'An unknown error occured.');
		}
	}
	
	public function fetchUniqueMatches(Request $request)
	{
		$term					= $request->term; 
		$matches				= array();
		$favorite_matches_array = array();
		$favorite_sports_array	= array();
		$data					= array();
		$userID					= Auth::user()->id;
		$favorite_matches 		= FavoriteMatches::select('event_id')->where('user_id',$userID)->get();
		$fav_matches_arr		= array();
		foreach($favorite_matches as $favorite_matche){
			$favorite_matches_array[] = $favorite_matche->event_id;
		}
		
		if( count($favorite_matches_array) ){
			$matches = BetfairEvents::select('name As match','startdatetime','id')->where('name','LIKE','%'.$term.'%')->whereNotIn('id',$favorite_matches_array)->where('status',1)->groupBy('name')->orderBy('startdatetime','asc')->get();
		}else{
			$matches = BetfairEvents::select('name As match','startdatetime','id')->where('name','LIKE','%'.$term.'%')->where('status',1)->groupBy('name')->orderBy('startdatetime','asc')->get();
		}
		if( count($matches)>0 ) {
			foreach( $matches as $match ) {
				$data['value'] = $match->id;
				$data['label'] = $match->match.' on '.date("D d M Y", strtotime($match->startdatetime)).' at '.date("h:i A", strtotime($match->startdatetime));
				array_push($fav_matches_arr, $data);
			}
		}
		
		return json_encode($fav_matches_arr);
	}
	
}
