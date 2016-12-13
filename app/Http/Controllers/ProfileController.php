<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Auth;
use App\User;
use App\MatesReqests;
use App\UsersMates;
use Hash;
use Session;
use DB;
use Validator;
use Redirect;
use App\FavoriteTeams;
use App\FavoriteSports;
use App\FavoriteMatches;
use App\Challenges;
use App\BetfairEvents;
use App\BetfairSports;

class ProfileController extends Controller
{
	
    public function getUsersProfile(Request $request)
	{
		$profile = Auth::user();
		return view('users.profile.profile', compact('profile'));
	}
	
	public function getUsersPreferencesTeams()
	{
		$team 		= array();
		$user_id 	= Auth::user()->id;
		$teams 		= FavoriteTeams::where('user_id',$user_id)->orderBy('created_at','desc')->get();
		foreach($teams as $team){
			$team_name = $team->name;
			$total_matches = BetfairEvents::Where(function ($query) use($team_name){
									$query->where('team1',$team_name)->orWhere('team2',$team_name);
								})->where('status',1)->where('result_declared',1)->get();
			$matches_won = 0;
			$matches_lost = 0;
			$challenges_won = 0;
			$challenges_lost = 0;
			
			foreach($total_matches as $match) {
				if ( $team_name == $match->team1) {
					$team_id = $match->team1_id;
				} else {
					$team_id = $match->team2_id;
				}
				
				$event_challenges = 0;
				$betfair_event_id 	= $match->id;
				$event_challenges = Challenges::where('betfair_event_id',$betfair_event_id)->count();
				
				if ( $team_id == $match->winner_id) {
					$matches_won	= $matches_won+1;
					$challenges_won += $event_challenges;
				} else {
					$matches_lost	= $matches_lost+1;
					$challenges_lost += $event_challenges;
				}
				
			} 
			$team->matches_won = $matches_won;
			$team->matches_lost = $matches_lost;
			$team->challenges_lost = $challenges_lost;
			$team->challenges_won = $challenges_won;
			$team->matches_total = $matches_won+$matches_lost;
			$team->challenges_total = $challenges_won+$challenges_lost;
			
		} 
		return view('users.profile.preferencres',compact('teams'));
	}

	public function getUsersPreferencesSports()
	{
		$sport 		= array();
		$user_id 	= Auth::user()->id;
		$sports 		= FavoriteSports::with('sport')->where('user_id',$user_id)->orderBy('created_at','desc')->get();
		return view('users.profile.preferencres_sports',compact('sports'));
	}
	
	public function getUsersPreferencesMatches()
	{
		$matches 			= array();
		$user_id 		= Auth::user()->id;
		$matches 		= FavoriteMatches::with('match')->where('user_id',$user_id)->orderBy('created_at','desc')->get();
		return view('users.profile.preferencres_matches',compact('matches'));
	}
	
	public function changePassword()
	{
		return view('users.profile.change_password');
	}
	
	//~ public function getUsersBettingStatement(Request $request)
	//~ {
		//~ $userID  = Auth::user()->id;
		//~ $filter  = $request->filter;
		//~ $summary = Challenges::with('mate','user','event','event.sports','product')->Where(function ($query) use($userID){
                //~ $query->where('user_id',$userID)
                      //~ ->orWhere('mate_id',$userID);
            //~ });
		//~ if( $filter && $filter =="awaiting" ){
			//~ $summary = $summary->where('challenge_status','awaiting');
		//~ }
		//~ if( $filter && $filter =="pending" ){
			//~ $summary = $summary->where('challenge_status','pending');
		//~ }
		//~ if( $filter && $filter =="active" ){
			//~ $summary = $summary->where('challenge_status','active');
		//~ }
		//~ if( $filter && $filter =="won" ){
			//~ $summary = $summary->where('challenge_status','won');
		//~ }
		//~ if( $filter && $filter =="lost" ){
			//~ $summary = $summary->where('challenge_status','lost');
		//~ }
		//~ $summary = $summary->orderBy('created_at', 'desc')->get();
		//~ return view('users.profile.betting_statement',compact('summary'));
	//~ }
	
	public function getUsersMates(Request $request)
	{
		$userMates	= array();
		$input 			= $request->input();
		$offset 		= 0; // $input['offset'];
		$limit			= 50;
		
		$authID			= Auth::user()->id;
		$userMates 		= UsersMates::with('mateProfile')->where('mate_id', $authID)->where('status', 'active')->limit($limit)->offset($offset)->get();
		
		foreach($userMates as $userMate){
			$mateID	= $userMate->mateProfile->id;
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
			$userMate->wagers_won = $wagers_won;
			$userMate->wagers_lost = $wagers_lost;
			$userMate->total_wagers = $wagers_won+$wagers_lost;
		}
		return view('users.profile.mates',  compact('userMates'));
	}
	
	public function getPendingInvitations(Request $request)
	{
		$matesRequests	= array();
		$input 			= $request->input();
		$offset 		= 0; // $input['offset'];
		$limit			= 50;
		
		$authID			= Auth::user()->id;
		$matesRequests 	= MatesReqests::with('mateProfile')->where('mate_id', $authID)->where('status', 'pending')->limit($limit)->offset($offset)->get();
		
		foreach($matesRequests as $matesRequest){
			$mateID	= $matesRequest->mateProfile->id;
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
			$matesRequest->wagers_won = $wagers_won;
			$matesRequest->wagers_lost = $wagers_lost;
			$matesRequest->total_wagers = $wagers_won+$wagers_lost;
		}
		return view('users.profile.pending_invitations',  compact('matesRequests'));
	}
	
	public function searchMates(Request $request)
	{
		$mates		 		 	= array();
		$final_array 		 	= array();
		$request_sent_array  	= array();
		$request_received_array = array();
		$page_number			= isset( $request->page_number ) ? $request->page_number : '1';
		$filters				= isset( $request->filters ) ? $request->filters : "false";
		$paginationFlag			= isset( $request->pagination ) ? $request->pagination : "false";
		$input 				 	= $request->input();
		$mates_per_page 		= config('constants.mates_per_page');
		$starting_position  	= (($page_number-1)*$mates_per_page);
		$userID = Auth::user()->id;
		
		// alreday mates of user
		$user_mates = UsersMates::select('user_id')->where('mate_id', $userID)->get();
		if( $user_mates ){
			foreach($user_mates as $user_mate){
				$final_array[] = $user_mate->user_id;
			}
		}
		
		// request sent or received by user
		$mate_requests = MatesReqests::Where(function ($query) use($userID){
									$query->where('user_id',$userID)->orWhere('mate_id',$userID);
								})->where('status','pending')->get();
		
		if( $mate_requests ){
			foreach($mate_requests as $mate_request){
				if($mate_request->user_id == $userID){
					$request_sent_array[] = $mate_request->mate_id;
				}else{
					$request_received_array[] = $mate_request->user_id;
				}
			}
		}
		// users own id
		$final_array[] = $userID;
		
		$mates 	= User::where('status', 1)->where('id','<>',$userID);
		// filter the results
		if ( isset($input['needle']) && $input['needle'] != '' ) {
			$needle	= trim($input['needle']);		
			$mates 	= $mates->where(function($query) use ($needle) {
				$query->where('firstname', 'LIKE', "%".$needle."%")->orWhere('lastname', 'LIKE', "%".$needle."%")->orWhere('email', 'LIKE',$needle)->orWhereRaw("concat_ws( ' ' , firstname, lastname ) LIKE '%".$needle."%'");
			});
		}
		
		if ( isset($input['rate_filter']) && $input['rate_filter'] != '' ) {
			$mates 	= $mates->where('star_rating', '=', trim($input['rate_filter']) );
		}
		
		$mates 	= $mates->orderBy('created_at', 'desc')->skip($starting_position)->take($mates_per_page)->get();
		
		//$this->printQuery();
		// get no.of wagers won and lost
		if( count($mates) ) {
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
		
		if( $filters == "true") {
			
			return view('users.profile.filters_search_mates_ajax', compact('mates','final_array','request_sent_array','request_received_array'))->render();
		} else {
			if( $paginationFlag == "true" ) {
				if( count($mates) ) {
					return view('users.profile.load_more_search_mates', compact('mates','final_array','request_sent_array','request_received_array'))->render();
				} else {
					return "false";
				}
			} else {
				return view('users.profile.search_mates', compact('mates','final_array','request_sent_array','request_received_array'));
			}
		}
		
		
		
	}
	
	public function editUsersProfile()
	{
		$profile = Auth::user();
		return view('users.profile.edit_profile', compact('profile'));
	}
	
	public function doEditUsersProfile(Request $request)
	{
		$input 			= $request->input();
		$profile 		= Auth::user();
		$original_value = $profile->email;
		
		if( $input['email'] != $original_value ) {
		   $is_unique 	=  '|unique:users';
		} else {
		   $is_unique 	=  '';
		}
		
		if ( null != $input ) {
			
			$client_rules = array(
				'firstname' 	=> 'required',
				'lastname'  	=> 'required',
				'email'  		=> 'required'.$is_unique,
			);

			$client_array = array(
				'firstname' 	=> $input['firstname'],
				'lastname'  	=> $input['lastname'],
				'email'  		=> $input['email']
			);

			$validator = Validator::make($client_array, $client_rules);
			
			if ( $validator->fails() ) {
				return Redirect::to('profile/edit')->withInput()->withErrors($validator);
			}

			$id 	= $profile->id;
			$update = [
						'firstname'    => $request->firstname,
						'lastname'     => $request->lastname,
						'email'        => $request->email,
						'mobile_phone' => isset($request->mobile_phone) ? $request->mobile_phone : NULL,
						'phone'        => isset($request->phone) ? $request->phone : NULL,
						'address1'     => isset($request->address1) ? $request->address1 : NULL,
						'address2'     => isset($request->address2) ? $request->address2 : NULL,
						'city'		   => isset($request->city) ? $request->city : NULL,	
						'state'        => isset($request->state) ? $request->state : NULL,
						'zipcode'      => isset($request->zipcode) ? $request->zipcode : NULL,
						'country'      => isset($request->country) ? $request->country : NULL,
			];
			$status = User::where('id', $id)->update($update);
			if ( $status ) {
				return Redirect::to('profile')->with('success', 'Profile updated successfully.');
			} else {
				return Redirect::to('profile')->with('error', 'Error occured while updating profile. Please try again later.');
			}
		} else {
			return Redirect::to('profile')->with('error', 'Error occured while updating profile. Please try again later.');
		}
	}
	
	public function validateUserEmail(Request $request)
	{
		$email 		= $request->fieldValue;
		$arrayToJs  = array();
		
		$this->validate($request, [
			'fieldValue' => 'required',
		]);
		
		$arrayToJs[0]  = $request->fieldId;
		
		$userID	= Auth::user()->id;
		$user	= User::where('email', $email)->where('id', '!=', $userID)->first();
			
		if (count($user)) {
			$arrayToJs[1] = false;
			$arrayToJs[2] = "Email already exists";
			echo json_encode($arrayToJs);
			exit;
		} else {
			$arrayToJs[1] = true;
			$arrayToJs[2] = "Good to go!";
			echo json_encode($arrayToJs);
			exit;
		}
	}
	
	public function doUploadImage(Request $request)
	{
		$rules = ['file' => 'mimes:jpg,jpeg,png'];
		$this->validate($request, $rules);
		$file = $request->file('file');
		$orignalName = $file->getClientOriginalName();
		$imageArray = explode('.', $orignalName);
		$uploadImage = str_random(30).'.'.$imageArray[1];
		
		$destinationPath = public_path() . "/assets/users/img/user_profile";
		
		
		$file->move($destinationPath, $uploadImage);
		$id = Auth::user()->id;
		$update = ['photo' => $uploadImage];
		$status = User::where('id', $id)->update($update);
		$src = url('assets/users/img/user_profile/' . $uploadImage);
		echo '<script>
		parent.document.getElementById("feedback").src="'.$src.'";
		parent.document.getElementById("feedback1").src="'.$src.'"
		</script>';
	}
	
	public function doChangePassword(Request $request)
	{
		//~ $rules= ['password' => 'required', 'new_password' => 'required', 're_password' => 'required|confirmed'];
		//~ $this->validate($request,$rules);
		$id = Auth::user()->id;
		$userPassword = User::where('id',$id)->pluck('password')->first();
		$password = $request->password;
		$newPassword = $request->new_password;
		if( Hash::check($password, $userPassword) ) {
			$update = ['password' => bcrypt($newPassword)];
			$status = User::where('id', $id)->update($update);
			if ( $status ) {
				return Redirect::to('profile/change-password')->with('success', 'Password updated successfully.');
			} else {
				return Redirect::to('profile/change-password')->with('error', 'Error occured while updating profile. Please try again later.');
			}
		} else {
			return Redirect::to('profile/change-password')->with('error', 'Invalid Password.');
		}
	}
	
	public function getSentPendingInvitations(Request $request)
	{
		$matesRequests	= array();
		$input 			= $request->input();
		$offset 		= 0; // $input['offset'];
		$limit			= 50;
		
		$authID			= Auth::user()->id;
		$matesRequests 	= MatesReqests::with('sentRequestMateProfile')->where('user_id', $authID)->where('status', 'pending')->limit($limit)->offset($offset)->get();
		
		foreach($matesRequests as $matesRequest){
			$mateID	= $matesRequest->mateProfile->id;
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
			$matesRequest->wagers_won = $wagers_won;
			$matesRequest->wagers_lost = $wagers_lost;
			$matesRequest->total_wagers = $wagers_won+$wagers_lost;
		}
		return view('users.profile.sent_pending_invitation',  compact('matesRequests'));
	}
	
	public function cancelMateRequest($id)
	{
		$requestID = $id;
		$status    = MatesReqests::where('id',$requestID)->delete();
		if( $status ){
			return back()->with('success','Request Cancelled Successfully');
		}else{
			return back()->with('error', 'Error occured while cancelling request. Please try again later.');
		}
	}
	
	public function getWonLossStatus($id)
	{

		$userID     	= $id;
		$data			= array();
		$challenges 	= Challenges::Where(function ($query) use($userID){
									$query->where('user_id',$userID)->orWhere('mate_id',$userID);
								})->where('winner_user_id','<>',Null)->get();
		$wagers_won 	= 0;
		$wagers_lost 	= 0;
		
		if ( count($challenges) ) {
			foreach ( $challenges as $challenge ) {
				
				if ( $challenge->winner_user_id == $userID ) {
					$wagers_won 	= $wagers_won+1;
				} else {
					$wagers_lost 	= $wagers_lost+1;
				}
			}
		}
		
		$total_wagers = $wagers_won + $wagers_lost;
		$data = array(array("total" => $total_wagers), array("status" => "Won", "count" => $wagers_won, "color" => "#71B37C"), array("status" => "Lost", "count" => $wagers_lost, "color" => "#ec8b8b"));
		return json_encode($data);
		
	}
}
