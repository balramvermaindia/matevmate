<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Validator;
use App\BetfairEvents;
use App\User;
use App\Products;
use App\Challenges;
use App\UsersMates;
use Hash;
use Session;
use DB;
use Redirect;

class ChallengeController extends Controller
{
    protected $passedMateID;
	public function __construct()
	{
		$this->passedMateID	= (isset($_GET['m']) && !empty($_GET['m'])) ? $_GET['m'] : 0;
		
	}
	
	public function selectTeam($eventID)
    {
		if ( $eventID ) {
			$passedMateID = $this->passedMateID;
			$challenMate = array();
			Session::put('challenMate', $challenMate);	
			$event = BetfairEvents::with('sports')->where('id', $eventID)->first();
			
			if ( count($event) ) {
				return view('users.challenge.make_bet', compact('event','passedMateID'));
			} else {
				return redirect('dashboard');
			}
		} else {
			return redirect('dashboard');
		}
	}
	
	public function selectMate(Request $request)
    {
		$input 					= $request->input();
		$mates					= array();
		$userMates				= array();
		$challenges				= array();
		$matesAlreadyChallenged	= array();
		$matesChallengedArray	= array();
		$page_number            = isset($input['page_number']) ? $input['page_number'] : 1;
		//echo $page_number; die;
		$mates_per_page         = config('constants.challenge_mates');
		$starting_position 		= (($page_number-1)*$mates_per_page);
		
		if( isset($input['hiddenField']) && !empty( $input['hiddenField'] ) ) {
			
			
		
			$client_rules = array(
				'event' 		=> 'required',
				'selectedTeam'  => 'required',
			);

			$client_array = array(
				'event' 		=> $input['selectedTeam'],
				'selectedTeam'  => $input['event']
			);

			$validator = Validator::make($client_array, $client_rules);
			
			if ( $validator->fails() ) {
				return 'false'; exit();
			}
		
			$challenMate	= array();
			$selectedteam 	= $input['selectedTeam'];
			$eventID 		= $input['event'];
			$challenMate 	= array("selectedTeam" => $selectedteam, "eventID" => $eventID);

			Session::put('challenMate', $challenMate);
			
			//selected mate from search mate page
			$passedMateID =  isset($request->passedMateID) ? $request->passedMateID : 0;
			
			
			if( $passedMateID > 0 ) {
				$mate_arr 		= array();
				array_push( $mate_arr, $passedMateID);
				$challengeMate	= Session::get('challenMate');
				$challengeMate['selectedMates'] = $mate_arr;
				Session::put('challenMate', $challengeMate);
				$wager 			= Products::where('status', 1)->orderBy('created_at', 'desc')->get();
				return view('users.challenge.select_wager_ajax', compact('wager'))->render();
				exit;
			}
			
		} else {
			
			$challengeMate	= Session::get('challenMate');
			$eventID		= $challengeMate['eventID'];
			
			if(isset($input['mate_arr']) && count($input['mate_arr'])> 0) {
				$selected_mate_arr = $input['mate_arr']; 
			} else {
				$selected_mate_arr = array();
			}
		}
		
			// user's own id
			$userID			= Auth::user()->id;
		
			$challenges		= Challenges::where('user_id', $userID)->where('betfair_event_id', $eventID)->get()->toArray();
		
		if ( count($challenges) > 0 ) {
			foreach( $challenges as $challenge ) {
				$matesAlreadyChallenged[] = $challenge['mate_id'];
			}
		}
		
		array_push($matesAlreadyChallenged,$userID);
		
		
		$userMates 	= User::where('status', 1)->whereNotIn('id',$matesAlreadyChallenged);
		
		//$userMates		= UsersMates::where('user_id', $userID)->where('status', 'active');
		//~ if ( count($matesAlreadyChallenged) > 0 ) {
			//~ $userMates 	= $userMates->where(function($query) use ($matesAlreadyChallenged) {
				//~ $query->whereNotIn('mate_id', $matesAlreadyChallenged);
			//~ });
		//~ }

		$userMates 		= $userMates->get()->toArray();
		
		if ( count($userMates) > 0 ) {
			foreach( $userMates as $userMate ) {
				$matesChallengedArray[] = $userMate['id'];
			}

			$mates 			= User::where('status', 1)->whereIn('id', $matesChallengedArray);

			if ( isset($input['needle']) && $input['needle'] != '' ) {
				$needle	= trim($input['needle']);		
				$mates 	= $mates->where(function($query) use ($needle) {
					$query->where('firstname', 'LIKE', "%$needle%")->orWhere('lastname', 'LIKE',"%$needle%")->orWhere('email', 'LIKE', "%$needle%")->orWhereRaw("concat_ws( ' ' , firstname, lastname ) LIKE '%".$needle."%'");
				});
			}
			
			if ( isset($input['rate_filter']) && $input['rate_filter'] != '' ) {
				$mates 	= $mates->where('star_rating', '=', trim($input['rate_filter']) );
			}
			
			$mates 	= $mates->orderBy('created_at', 'desc')->skip($starting_position)->take($mates_per_page)->get();
			// get no.of wagers won and lost
			foreach($mates as $mate){
				$mateID	= $mate->id;
				$challenges = Challenges::Where(function ($query) use($mateID){
										$query->where('user_id',$mateID)->orWhere('mate_id',$mateID);
									})->where('winner_user_id','<>',Null)->get();
				$wagers_won = 0;
				$wagers_lost = 0;
				if($challenges){
					foreach($challenges as $challenge){
						if($challenge->winner_user_id == $mateID){
							$wagers_won = $wagers_won+1;
						}else{
							$wagers_lost = $wagers_lost+1;
						}
					}
				}
				$mate->wagers_won = $wagers_won;
				$mate->wagers_lost = $wagers_lost;
				$mate->total_wagers = $wagers_won+$wagers_lost;
			}
		}
		
		if( isset($input['filters']) && !empty($input['filters']) || isset($input['needle']) && !empty($input['needle'])){
			if( isset($input['pagination']) )  {
				if( count($mates) ) {
					
					return view('users.challenge.load_more_select_mate_ajax', compact('mates','passedMateID','selected_mate_arr'))->render();
				} else {
					
					return "false";
				}
			} else {
				return view('users.challenge.filter_select_mate_ajax', compact('mates','passedMateID','selected_mate_arr'))->render();
			}
		}
		
		if( isset($input['pagination']) )  {
			if( count($mates) ) {
				return view('users.challenge.load_more_select_mate_ajax', compact('mates','passedMateID'))->render();
			} else {
				return "false";
			}
		} else {
			return view('users.challenge.select_mate_ajax', compact('mates','passedMateID'))->render();
		}
	}
	
	public function selectWager( Request $request ){
		$input = $request->input();
	
		$mate_arr			= $input['mate_arr'];
		$challengeMate	= Session::get('challenMate');
		
		if ( !count($challengeMate) ) {
			return 'false';
		}
		
		if ( !count($mate_arr) ) {
			return 'false';
		}
		
		$challengeMate['selectedMates'] = $mate_arr;
		Session::put('challenMate', $challengeMate);
	
		
		return view('users.challenge.select_wager_ajax', compact('wager'))->render();
		
	}
	
	
		public function reviewWager(Request $request)
		{
			$wager			= array();
			$input 			= $request->input();
			$wager_amount		= $input['wager_amount'];
			$challengeMate	= Session::get('challenMate');
			if ( !count($challengeMate) ) {
				return 'false';
			}
			
			if ( !count($wager_amount) ) {
				return 'false';
			}
			
			$challengeMate['wagerData'] = $wager_amount;
			Session::put('challenMate', $challengeMate);
			
			$challengeMate	= Session::get('challenMate');
			$eventID		= $challengeMate['eventID'];
			$selectedTeam	= $challengeMate['selectedTeam'];
			$event			= BetfairEvents::with('sports')->where('id', $eventID)->first();
			
			$selUsers		= $challengeMate['selectedMates'];
			$mate			= array();
			if (count($selUsers)) {
				$i = 0;
				foreach($selUsers as $selUser) {
					$mate[$i]['mate'] = User::where('status', 1)->where('id', $selUser)->first();
					$i++;
				}
			}
			$challengeMate['finalMateArray'] = $mate;
			Session::put('challenMate', $challengeMate);
		
			return view('users.challenge.review_wager_ajax', compact('event', 'mate', 'selectedTeam' ,'wager_amount'))->render();
	}
		
	public function challengeMate(Request $request)
	{
		$input 			= $request->input();
		$challengeMate	= Session::get('challenMate');
		$eventID		= $challengeMate['eventID'];
		$selectedteam	= $challengeMate['selectedTeam'];
		$wager_amount   = $challengeMate['wagerData'];
		$event			= BetfairEvents::where('id', $eventID)->first();
		$col			= $selectedteam.'_id';
		$selectedTeamID	= $event->$col;
		$userID			= Auth::user()->id;
		$editMode		= (isset($input['edit']) && $input['edit'] == 'true') ? "true" : "false";
		$challengeID	= (isset($input['hash']) && !empty($input['hash'])) ? $input['hash'] : 0;

		
		foreach ( $challengeMate['finalMateArray'] as $mates) {
			$selectedUser					= $mates['mate']->id;
			//$selectedWager					= $mates['wager']->id;
			
			if ( $editMode == 'true' &&  $challengeID > 0 ) {
				$currentChallengeStatus		= Challenges::where('id', $challengeID)->first();
				$currentChallengeStatus		= $currentChallengeStatus->accepted_status;
				if ( $currentChallengeStatus == 'pending' ) {
					$update = [
							'user_id'    		=> $userID,
							'mate_id'     		=> $mates['mate']->id,
							'challenge_mode'    => 'mate',
							'challenge_status' 	=> 'awaiting',
							'accepted_status'   => 'pending',
							'betfair_event_id'  => $eventID,
							'team_id'     		=> $selectedTeamID,
							'product_id'		=> 0,
							'wager_amount'	    => $wager_amount,
							'product_quantity'  => 1,
							'status'      		=> 'active',
							'honour_status'     => 'pending',
					];
					$saved = Challenges::where('id', $challengeID)->update($update);
				} else {
					return 'false';
				}
				
			} else {
				$challenge 						= new Challenges();
				$challenge->user_id				= $userID;
				$challenge->mate_id				= $mates['mate']->id;
				$challenge->challenge_mode		= 'mate';
				$challenge->betfair_event_id	= $eventID;
				$challenge->team_id				= $selectedTeamID;
				$challenge->product_id			= 0;
				$challenge->wager_amount	    = $wager_amount;
				$saved 							= $challenge->save();
			}
			
		
			$canWeSendNotificationToUser = $this->checkIfUserWantNotification($userID, 'CHALLENGEAMATE'); // Check if challenger want to be notified about this
			
			$canWeSendNotificationToMate = $this->checkIfUserWantNotification($selectedUser, 'SOMEBODYCHALLENGEDYOU'); // Check if mate want to be notified about this
			
			$authProfile				= User::where('status', 1)->where('id', $userID)->first();
			$userName					= ucwords(@$authProfile->firstname . ' ' . @$authProfile->lastname);
			
			$mateProfile				= User::where('status', 1)->where('id', $selectedUser)->first();
			$mateName					= ucwords(@$mateProfile->firstname . ' ' . @$mateProfile->lastname);
			
			//$wager 						= Products::where('status', 1)->where('id', $selectedWager)->first();
			$wager 						= Challenges::where('id', $challenge->id)->first();
				
				if ( $canWeSendNotificationToUser ) {
					$to      = $authProfile->email;
					$subject = "Challenge Placed Successfully - MateVMate";
					$message = '<html><body>';
					$message .= "Hello " . @$userName . ",<br><br>";
					$message .= "Congrats! You have successfully placed a challenge on MateVMate. Here are the challenge details -<br>";
					$message .= "Match - " . $event->name . "<br>";
					$message .= "Wager is on - " . $event->$selectedteam . " to win<br>";
					$message .= "Wager made with - " . $mateName . "<br>";
					$message .= "Wager at stake - " . $wager->wager_amount . "<br><br><br>";
					$message .= "Thanks <br>";
					$message .= "MateVMate";
					$message .= "</body></html>";
					$this->sendEmail($to, $subject, $message);
				}
				
				if ( $editMode == 'true' &&  $challengeID > 0 ) {
					$this->sendNotification( $selectedUser, 'placechallenge', $challengeID );
				} else {
					$this->sendNotification( $selectedUser, 'placechallenge', $challenge->id );
				}

			if ( $canWeSendNotificationToMate ) {
				$html = '';				
				
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
								//~ <span>'.@$userName.' </span> placed a challenge against you.
								//~ <p><i class="fa fa-calendar" aria-hidden="true"></i> '.date('d M, Y').'</p>
							//~ </div>
							//~ <div class="list-col cmts matchs_btn">
								//~ <a class="cmt_btn" target="_blank" href="'.url("challenge/$challenge->id").'">View Details</a>
							//~ </div>
						//~ </div>';
				
				
				
				$to      = $mateProfile->email;
				$subject = "A New Challenge Placed Against You - MateVMate";
				$message = '<html><body>';
				$message .= "Hello " . @$mateName . ",<br><br>";
				$message .= $userName . " placed a challenge against you on MateVMate. Here are the challenge details -<br><br>";
				$message .= "Match - " . $event->name . "<br>";
				$message .= "Wager is on - " . $event->$selectedteam . " to win<br>";
				$message .= "Wager at stake - " . $wager->wager_amount . "<br><br><br>";
				$message .= "Thanks <br>";
				$message .= "MateVMate";
				$message .= "</body></html>";
				
				$this->sendEmail($to, $subject, $message);
			}
		}
		print 'success';
		
	}
	
	public function getMatesForWager(Request $request)
	{
		$data			= array();
		$input 			= $request->input();
		$wager_id		= $input['wager_id'];
		$challengeMate	= Session::get('challenMate');
		
		if ( isset($challengeMate['selectedIndividualMatesForWager']) && count($challengeMate['selectedIndividualMatesForWager']) ) {
			$selectedUsers	= User::where('status', 1)->whereNotIn('id', $challengeMate['selectedIndividualMatesForWager'])->whereIn('id', $challengeMate['selectedMates'])->select('id', 'firstname', 'lastname')->get()->toArray();
		} else {
			$selectedUsers	= User::where('status', 1)->whereIn('id', $challengeMate['selectedMates'])->select('id', 'firstname', 'lastname')->get()->toArray();
		}

		return $selectedUsers;
	}
	
	public function applyWager(Request $request)
	{
		$data			= array();
		
		$input 			= $request->input();
		$wager_id		= $input['wager_id'];
		$selUser		= $input['selUser'];
		
		$challengeMate	= Session::get('challenMate');

		if ( !count($challengeMate) ) {
			return 'false';
		}
		
		if ( !count($selUser) ) {
			return 'false';
		}
		
		if ( isset($challengeMate['selectedIndividualMatesForWager']) ) {
			foreach ($selUser as $selUsers) {
				$challengeMate['selectedIndividualMatesForWager'][] = $selUsers;
			}
		} else {
			$challengeMate['selectedIndividualMatesForWager']	= $selUser;
		}
		
		if ( isset($challengeMate['wagerData']) ) {
			foreach ( $challengeMate['wagerData'] as $wagerData ) {
				if ($wagerData['wager_id'] == $wager_id) {
					array_push($challengeMate['wagerData']['wager_mates'], $input['selUser']);
				} else {
					//$challengeMate['wagerData'] = array(array("wager_id" => $wager_id, "wager_mates" => $selUser));
					$tdata = array("wager_id" => $wager_id, "wager_mates" => $selUser);
					array_push($challengeMate['wagerData'], $tdata);
					break;
				}
			}
		} else {
			$challengeMate['wagerData'] = array(array("wager_id" => $wager_id, "wager_mates" => $selUser));
		}
		
		Session::put('challenMate', $challengeMate);
		
		return 'true';
	}
	
	public function getChallengeDetail($id)
	{
		Challenges::findOrFail($id);
		$user_id = Auth::user()->id;
		
		$challenge = Challenges::with('event','mate','product','user')->where('id',$id)->Where(function ($query) use($user_id){
                $query->where('user_id',$user_id)
                      ->orWhere('mate_id',$user_id);
            })->first();
           
        if( empty($challenge) ){
			return redirect('dashboard');
		}
		return view('users.challenge.challenge_details',compact('challenge'));
	}
	
	public function doCancelChallenge($id)
	{
		Challenges::findOrFail($id);
		$delete = Challenges::where('id',$id)->delete();
		if( $delete ) {
			return redirect('dashboard')->with('success','Challenge cancelled successfully');
		}else{
			return back()->with('error','There was some error. Please try again in some time.');
		}
	}
	
	public function doCancelHonourWager($id)
	{
		Challenges::findOrFail($id);
		$challenge = Challenges::with('event','product')->where('id',$id)->first();
		$selectedTeam = "";
		$wager        = $challenge->product->name;
		$event		  = $challenge->event->name;	
		if( $challenge->team_id == $challenge->event->team1_id ){
			$selectedTeam = $challenge->event->team1;
		}else{
			$selectedTeam = $challenge->event->team2;
		}
		$update    = array(
						'honour_status' => 'cancelled',
						);
		$status    = Challenges::where('id',$id)->update($update);
		if( $status ){
			$userID = $challenge->winner_user_id;
			$typeID = $id;
			$this->sendNotification($userID, 'honourchallenge', $typeID);
			
			$canWeSendEmail = $this->checkIfUserWantNotification($userID, 'WAGERHONOURED');
			
			if( $canWeSendEmail ){
				$winner			 		= User::where('status', 1)->where('id', $userID)->first();
				$winner_name 	 		= ucwords(@$winner->firstname . ' ' . @$winner->lastname);
				$looser					= Auth::user();
				$looser_name 			= ucwords(@$looser->firstname . ' ' . @$looser->lastname);
				
				$to      = $winner->email;
				$subject = "Cancelled Honour Wager- MateVMate";
				$message = '<html><body>';
				$message .= "Hello " . @$winner_name . ",<br><br>";
				$message .= @$looser_name ." cancelled the  wager. Here are the challenge details: <br><br>";
				$message .= "Match - " . $event . "<br>";
				$message .= "Wager is on - " . $selectedTeam . " to win<br>";
				$message .= "Wager at stake - " . $wager . "<br><br><br>";
				$message .= "Please login to your MateVMate account for more details. <br><br><br><br>";
				$message .= "Thanks <br>";
				$message .= "MateVMate";
				$message .= "</body></html>";
				
				$this->sendEmail($to, $subject, $message);
			}
			
			return back()->with('success','honour cancelled successfully.');
		} else {
			return back()->with('error','There was some error. Please try again in some time.');
		}
	}
	
	public function postChallengeToBanter($id)
	{
		Challenges::findOrFail($id);
		$update    = array(
						'mate_id' => '0',
						'challenge_mode' => 'banter',
						'challenge_status' => 'awaiting',
						'accepted_status' => 'pending'
						);
		$status    = Challenges::where('id',$id)->update($update);
		if( $status ){
			return redirect('dashboard')->with('success','Challenge posted to banter board successfully.');
		}else{
			return back()->with('error','There was some error. Please try again in some time.');
		}
	}
	public function searchOtherMates(Request $request)
	{
		$userID = Auth::user()->id;
		$term   = $request->term;
		$mates_ID_array = array();
		$mateID[] = $request->mateID;
		
		$mate_arr = array();
		
		$mates_ID = UsersMates::where('user_id',$userID)->select('mate_id')->whereNotIn('mate_id',$mateID)->where('status','active')->get();
		
		foreach( $mates_ID as $mate_ID ){
			$mates_ID_array[] = $mate_ID->mate_id;
		}
		
		$mates_profile = User::whereIn('id',$mates_ID_array)->where('status',1)->Where(function ($query) use($term){
									$query->where('firstname','LIKE','%'.$term.'%')->orWhere('lastname','LIKE','%'.$term.'%')->orWhereRaw("concat_ws( ' ' , firstname, lastname ) LIKE '%".$term."%'");
									})->get();
		if($mates_profile){ 							
			foreach ($mates_profile as $mate_profile)
			{
				$data['value'] = $mate_profile->id;
				$data['label'] = ucfirst($mate_profile->firstname).' '.ucfirst($mate_profile->lastname);
				array_push($mate_arr,$data);
			}
		}
		//dd($mate_arr);
		
		return json_encode($mate_arr);
		
	}
	
	public function doSelectOtherMate(Request $request)
	{
		$challengeID = $request->challengeID;
		$mateID = $request->mateID;
		$challenge = Challenges::with('event','product')->where('id',$challengeID)->first();
		$userID    = $challenge->user_id;
		$update = array(
				'challenge_status' => 'awaiting',
				'accepted_status' => 'pending',
				'mate_id'         => $mateID
		);
		$status    = Challenges::where('id',$challengeID)->update($update);
		if( $status ){
			$this->sendNotification( $mateID, 'placechallenge', $challengeID );
			
			$canWeSendMailToUser = $this->checkIfUserWantNotification($userID, 'CHALLENGEAMATE'); // Check if challenger want to be notified about this
				
			$canWeSendMailToMate = $this->checkIfUserWantNotification($mateID, 'SOMEBODYCHALLENGEDYOU'); // Check if mate want to be notified about this
			
				$authProfile				= User::where('status', 1)->where('id', $userID)->first();
				$userName					= ucwords(@$authProfile->firstname . ' ' . @$authProfile->lastname);
				
				$mateProfile				= User::where('status', 1)->where('id', $mateID)->first();
				$mateName					= ucwords(@$mateProfile->firstname . ' ' . @$mateProfile->lastname);
				
				if($challenge->team_id == $challenge->event->team1_id){
					$selectedteam = $challenge->event->team1;
				}else{
					$selectedteam = $challenge->event->team2;
				}
				echo $canWeSendMailToUser; die;
				if ( $canWeSendMailToUser ) {
						$to      = $authProfile->email;
						$subject = "Challenge Placed Successfully - MateVMate";
						$message = '<html><body>';
						$message .= "Hello " . @$userName . ",<br><br>";
						$message .= "Congrats! You have successfully placed a challenge on MateVMate. Here are the challenge details -<br>";
						$message .= "Match - " . $challenge->event->name . "<br>";
						$message .= "Wager is on - " . $selectedteam . " to win<br>";
						$message .= "Wager made with - " . $mateName . "<br>";
						$message .= "Wager at stake - " . $challenge->product->name. "<br><br><br>";
						$message .= "Thanks <br>";
						$message .= "MateVMate";
						$message .= "</body></html>";
						$this->sendEmail($to, $subject, $message);
					}
				if ( $canWeSendMailToMate ) {
					
					$to      = $mateProfile->email;
					$subject = "A New Challenge Placed Against You - MateVMate";
					$message = '<html><body>';
					$message .= "Hello " . @$mateName . ",<br><br>";
					$message .= $userName . " placed a challenge against you on MateVMate. Here are the challenge details -<br><br>";
					$message .= "Match - " . $challenge->event->name . "<br>";
					$message .= "Wager is on - " . $selectedteam  . " to win<br>";
					$message .= "Wager at stake - " . $challenge->product->name . "<br><br><br>";
					$message .= "Thanks <br>";
					$message .= "MateVMate";
					$message .= "</body></html>";
					
					$this->sendEmail($to, $subject, $message);
				}
				return 1;	
		}else{
			return 0;
		}
	}
	
	//~ public function filterChallengeMates(Request $request)
	//~ {
		//~ $needle = $request->
	//~ }
}
