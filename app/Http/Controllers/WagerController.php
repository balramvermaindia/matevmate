<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use App\User;
use App\Products;

use App\Challenges;
use Redirect;

class WagerController extends Controller
{
    public function getUsersWagers(Request $request)
	{
		$userID 		 	= Auth::user()->id;
		$filter  		 	= $request->filter;
		$summary        	= array();
		$paginationFlag 	= isset($request->pagination) ? $request->pagination : "false"; 
		$page_number        = isset($request->page_number) ? $request->page_number : 1; 
		$wagers_per_page	= config('constants.my_wagers');
		$starting_position	= ( ( $page_number-1 ) * $wagers_per_page );
		$summary 			= Challenges::with('mate','user','event','event.sports','product')->Where(function ($query) use						($userID){
								 $query->where('user_id',$userID)->orWhere('mate_id',$userID);
							})->where('challenge_mode','mate');
		 
         if( isset($filter) && !empty($filter) ){
			 if( $filter == "awaiting" || $filter == "pending" || $filter == "active"){ 
				$summary = $summary->where('challenge_status',$filter);
			}
			if( $filter == "lost"){
				$summary = $summary->where('challenge_status',$filter)->where('winner_user_id','<>',$userID);
			}
			if( $filter == "won"){
				$summary = $summary->where('challenge_status',$filter)->where('winner_user_id',$userID);
			}
		 }
		 
		$summary = $summary->orderBy('created_at', 'desc')->skip($starting_position)->take($wagers_per_page)->get();
		
		if( isset($filter) && !empty($filter) && $paginationFlag == "false" ) {
				return view('users.wager.filters_my_wagers_ajax',compact('summary'))->render();
		} else {
			if( $paginationFlag == "true") {
				if( count($summary) ) {
					return view('users.wager.load_more_my_wagers_ajax',compact('summary'))->render();
				} else {
					return "false";
				}
			} else {
				return view('users.wager.my_wagers',compact('summary'));
			}
		}
	}
	
	public function getOpenPublicWagers()
	{
		$open_public_wagers = array();
		$currentDateTime 	= date('Y-m-d\TH:i:s\Z');
		$userID				= Auth::user()->id;
		$open_public_wagers = array();
		$open_public_wagers = Challenges::with('user','event','product')->whereHas('event', function ($query) use($currentDateTime) {
							$query->where('startdatetime', '>=', $currentDateTime);	
						 })->where('challenge_mode','banter')->where('challenge_status','awaiting')->where('accepted_status','pending')->get();
		return view('users.wager.open_public_wagers',compact('open_public_wagers'));
	}
	
	//~ public function honor($challange_id)
	//~ {
		//~ $challenges = Challenges::with('event', 'mate', 'product', 'user')->Where('id', $challange_id)->first();
		//~ $wager 		= Products::where('id',$challenges->product_id)->first();
		//~ return view('users.wager.honor',compact('challenges', 'wager'));
	//~ }
	
	//~ public function thankyou(Request $request)
	//~ {
		//~ $id = $request->id;
		//~ Challenges::findOrFail($id);
		//~ $challenge = Challenges::with('event','product')->where('id',$id)->first();
		//~ $selectedTeam = "";
		//~ $wager        = $challenge->product->name;
		//~ $event		  = $challenge->event->name;	
		//~ if( $challenge->team_id == $challenge->event->team1_id ){
			//~ $selectedTeam = $challenge->event->team1;
		//~ }else{
			//~ $selectedTeam = $challenge->event->team2;
		//~ }
		//~ $update    = array(
						//~ 'honour_status' => 'completed',
						//~ );
		//~ $status    = Challenges::where('id',$id)->update($update);
		//~ if( $status ){
			//~ $userID = $challenge->winner_user_id;
			//~ $typeID = $id;
			//~ $this->sendNotification($userID, 'honourchallenge', $typeID);
			//~ 
			//~ $canWeSendEmail = $this->checkIfUserWantNotification($userID, 'WAGERHONOURED');
			//~ 
			//~ if( $canWeSendEmail ){
				//~ $winner			 		= User::where('status', 1)->where('id', $userID)->first();
				//~ $winner_name 	 		= ucwords(@$winner->firstname . ' ' . @$winner->lastname);
				//~ $looser					= Auth::user();
				//~ $looser_name 			= ucwords(@$looser->firstname . ' ' . @$looser->lastname);
				//~ 
				//~ $to      = $winner->email;
				//~ $subject = "Honoured The Wager- MateVMate";
				//~ $message = '<html><body>';
				//~ $message .= "Hello " . @$winner_name . ",<br><br>";
				//~ $message .= @$looser_name ." honoured the  wager. Here are the challenge details: <br><br>";
				//~ $message .= "Match - " . $event . "<br>";
				//~ $message .= "Wager is on - " . $selectedTeam . " to win<br>";
				//~ $message .= "Wager at stake - " . $wager . "<br><br><br>";
				//~ $message .= "Please login to your MateVMate account for more details. <br><br><br><br>";
				//~ $message .= "Thanks <br>";
				//~ $message .= "MateVMate";
				//~ $message .= "</body></html>";
				//~ 
				//~ $this->sendEmail($to, $subject, $message);
			//~ }
			//~ 
			//~ return view('users.wager.thankyou')->with('success','honoured the wager successfully.');
		//~ } else {
			//~ return back()->with('error','There was some error. Please try again in some time.');
		//~ }
	//~ }
	
	public function accept($challange_id){
		$challenges                     = Challenges::Where('id', $challange_id)->first();
		$challenger						= $challenges->user_id;
		$challenges->accepted_status 	= 'accepted';
		$challenges->challenge_status 	= 'pending';
		$save                           =  $challenges->save();
		if( $save ){
			$userID						= $challenges->user_id;
			$typeID						= $challenges->id;
			$this->sendNotification($userID, 'acceptchallenge', $typeID);
			
			$canWeSendEmail = $this->checkIfUserWantNotification($userID, 'CHALLENGEACCEPTEDDECLINED');
			
			if( $canWeSendEmail ){
				$challenger			 		= User::where('status', 1)->where('id', $challenger)->first();
				$challenger_name 	 		= ucwords(@$challenger->firstname . ' ' . @$challenger->lastname);
				$challenger_accepter 		= Auth::user();
				$challenger_accepter_name 	= ucwords(@$challenger_accepter->firstname . ' ' . @$challenger_accepter->lastname);
				
				$to      = $challenger->email;
				$subject = "Challenge Request Accepted - MateVMate";
				$message = '<html><body>';
				$message .= "Hello " . @$challenger_name . ",<br><br>";
				$message .= @$challenger_accepter_name ." accepted your challenge request. <br>";
				$message .= "Please login to your MateVMate account for more details. <br><br><br><br>";
				$message .= "Thanks <br>";
				$message .= "MateVMate";
				$message .= "</body></html>";
				
				$this->sendEmail($to, $subject, $message);
			}
			
			return Redirect::to('/challenge-detail/'.$challange_id)->with('success','Challenge accepted successfully');
		}else{
			return Redirect::to('/challenge-detail/'.$challange_id)->with('error','Some error occured while accepting challenge. Please try again in some time.');
		}
		
	}
	 
	public function decline($challange_id){
		$challenges = Challenges::Where('id', $challange_id)->first();
		$challenger						= $challenges->user_id;
		$challenges->accepted_status 	= 'rejected';
		$challenges->challenge_status 	= 'rejected';
		$save							=  $challenges->save();
		if( $save ){
			$userID						= $challenger;
			$typeID						= $challenges->id;
			$this->sendNotification($userID, 'rejectchallenge', $typeID);
			
			$canWeSendEmail = $this->checkIfUserWantNotification($challenger, 'CHALLENGEACCEPTEDDECLINED');
			
			if( $canWeSendEmail ){
				$challenger			 		= User::where('status', 1)->where('id', $challenger)->first();
				$challenger_name 	 		= ucwords(@$challenger->firstname . ' ' . @$challenger->lastname);
				$challenger_accepter 		= Auth::user();
				$challenger_accepter_name 	= ucwords(@$challenger_accepter->firstname . ' ' . @$challenger_accepter->lastname);
				
				$to      = $challenger->email;
				$subject = "Challenge Request Rejected - MateVMate";
				$message = '<html><body>';
				$message .= "Hello " . @$challenger_name . ",<br><br>";
				$message .= @$challenger_accepter_name ." rejected your challenge request. <br>";
				$message .= "Please login to your MateVMate account for more details. <br><br><br><br>";
				$message .= "Thanks <br>";
				$message .= "MateVMate";
				$message .= "</body></html>";
				
				$this->sendEmail($to, $subject, $message);
			}
			
			return Redirect::to('/challenge-detail/'.$challange_id)->with('success','Challenge declined successfully');
		}else{
			return Redirect::to('/challenge-detail/'.$challange_id)->with('error','Some error occured while accepting challenge. Please try again in some time.');
		}
	} 
	
	public function doAcceptOpenPublicWager($id)
	{
		$challenge   = Challenges::findOrFail($id);
		$challengeID = $id;
		$userID		 = Auth::user()->id;
		$update		 = array(
							'mate_id'			=> $userID,
							'challenge_mode'	=> 'mate',
							'challenge_status'	=> 'pending',
							'accepted_status'  	=> 'accepted'
					      );
					      
		$status = Challenges::where('id',$challengeID )->update($update);
		if( $status ){
			$challengerID = $challenge->user_id;
			$typeID		  = $challenge->id;
			$this->sendNotification($challengerID, 'acceptchallenge', $typeID);
			
			$canWeSendEmail = $this->checkIfUserWantNotification($challengerID, 'CHALLENGEACCEPTEDDECLINED');
			
			if( $canWeSendEmail ){
				$challenger			 		= User::where('status', 1)->where('id', $challengerID)->first();
				$challenger_name 	 		= ucwords(@$challenger->firstname . ' ' . @$challenger->lastname);
				$challenger_accepter 		= Auth::user();
				$challenger_accepter_name 	= ucwords(@$challenger_accepter->firstname . ' ' . @$challenger_accepter->lastname);
				
				$to      = $challenger->email;
				$subject = "Challenge Request Accepted - MateVMate";
				$message = '<html><body>';
				$message .= "Hello " . @$challenger_name . ",<br><br>";
				$message .= @$challenger_accepter_name ." accepted your challenge request. <br>";
				$message .= "Please login to your MateVMate account for more details. <br><br><br><br>";
				$message .= "Thanks <br>";
				$message .= "MateVMate";
				$message .= "</body></html>";
				
				$this->sendEmail($to, $subject, $message);
			}
			return redirect('wagers')->with('success','Challenge accepted successfully');
			
		}else{
			return back()->with('error','Some error occured while accepting challenge. Please try again in some time.');
		}
		
	}
}
