<?php
	use App\User;
	use App\MatesReqests;
	use App\Challenges;
	
	function getNotificationDetail($type, $type_id )
    {
		$detail = array();
		if($type == "acceptmaterequest" || $type == "addmate" || $type == "rejectmaterequest" || $type == "wonchallenge" || $type == "lostchallenge" ){
			$detail = User::where('id',$type_id)->where('status','1')->first();
		}elseif( $type == "placechallenge" || $type == "acceptchallenge" || $type == "rejectchallenge" || $type == "honourchallenge"){
			$detail = Challenges::with('mate','user')->where('id', $type_id )->first();
		}
		
		return $detail;
	}
	
	function getRequestStatus( $sender, $receiver )
	{

		$status = array();
		$status = MatesReqests::where('user_id',$sender)->where('mate_id', $receiver )->first();
		return $status;
	}
	
	function getMateProfileDetail( $challenge_id, $userID)
	{
		$challenge = Challenges::findOrFail($challenge_id);
		if($challenge->user_id == $userID){
			$detail = User::where('id',$challenge->mate_id)->where('status','1')->first();
		}else{
			$detail = User::where('id',$challenge->user_id)->where('status','1')->first();
		}
		return $detail;
	}
	
	function showDateTime($input, $timezone, $format)
	{
		$userTimeZone = (null != Session::get('userTimeZone')) ? Session::get('userTimeZone') : "UTC";
		$query = "SELECT CONVERT_TZ('".$input."', '".$timezone."', '".$userTimeZone."' ) utcdate";
		$result = \DB::select($query);
		if(count($result))
		{
			$formatted_date = date($format, strtotime($result[0]->utcdate));
		}
		else
		{
			date_default_timezone_set($userTimeZone);
			$formatted_date = date($format, strtotime($input));
		}
		
		if($_SERVER['HTTP_HOST']=="localhost")
		{
			date_default_timezone_set($userTimeZone);
			$formatted_date = date($format, strtotime($input));
		}
		echo $formatted_date;
	}
	
	function getSportsImageBySportsID( $id )
	{
		switch ($id) {
		case '2':
			return $img_url = "assets/users/img/p4.png";
			break;
		case '3':
			return $img_url = "assets/users/img/p6.png";
			break;
		case '4':
			return $img_url = "assets/users/img/p7.png";
			break;
		case '6':
			return $img_url = "assets/users/img/p9.png";
			break;
		case '7':
			return $img_url = "assets/users/img/p12.png";
			break;
		case '9':
			return $img_url = "assets/users/img/p14.png";
			break;
		case '10':
			return $img_url = "assets/users/img/p13.png";
			break;
		case '11':
			return $img_url = "assets/users/img/p17.png";
			break;
		case '12':
			return $img_url = "assets/users/img/p15.png";
			break;
		case '13':
			return $img_url = "assets/users/img/p16.png";
			break;
		case '14':
			return $img_url = "assets/users/img/s25.png";
			break;
		case '15':
			return $img_url = "assets/users/img/p5.png";
			break;
		 default:
			return $img_url = "assets/users/img/p16.png";
		} 
	}
