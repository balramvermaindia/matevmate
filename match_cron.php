<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SERVER['HTTP_HOST'] == 'localhost') {
	$db = mysqli_connect('192.168.1.77', 'root', 'root', 'matevmate');
}else {
	$db = mysqli_connect('localhost', 'shineweb_sratool', '[~vc2E-oV*hX', 'shineweb_matevmate');
}
$query					= "select betfair_events.* from betfair_events join challenges where betfair_events.id = challenges.betfair_event_id";
$get_events 			= mysqli_query($db, $query);
$total_events_query_rows	= mysqli_affected_rows($db);
if ($total_events_query_rows > 0) {
	while ($events = mysqli_fetch_assoc($get_events)) {
		$winner			= getWinner();
		$winner_id		= $events[$winner];
		$update_query	= "update betfair_events set winner_id = ".$winner_id." , result_declared = 1 where id = ".$events['id'];
		mysqli_query($db, $update_query);
	}
}

$get_challenge_query			= "select * from challenges where accepted_status= 'accepted' and (challenge_status = 'active' OR challenge_status = 'pending' )";
$get_challenges					= mysqli_query($db, $get_challenge_query);
$total_challenges_query_rows	= mysqli_affected_rows($db);
if ($total_challenges_query_rows > 0) {
	while($challenge = mysqli_fetch_assoc($get_challenges)){
		$betfair_event_id				= $challenge['betfair_event_id'];
		$get_bitfair_event_query		= "select * from betfair_events where id =".$betfair_event_id;
		$get_bitfair_event				= mysqli_query($db, $get_bitfair_event_query);
		$total_bitfair_event			= mysqli_affected_rows($db);
		if($total_bitfair_event > 0){
			$bitfair_event 	= mysqli_fetch_assoc($get_bitfair_event);
			$winner_id		= $bitfair_event['winner_id'];
			if($bitfair_event['result_declared'] == 1){
				if($winner_id == $challenge['team_id']){
					$challenge_status 	= 'won';
					$winner_user_id		= $challenge['user_id'];
				}else if($winner_id == 0){
					$challenge_status 	= 'draw';
					$winner_user_id		= 0;
				}else{
					$challenge_status 	= 'lost';
					$winner_user_id		= $challenge['mate_id'];
				}
				$update_challange_status_query	= "update challenges set challenge_status = '".$challenge_status."', winner_user_id=".$winner_user_id.",  status= 'complete' where id =".$challenge['id'];
				
				mysqli_query($db, $update_challange_status_query);
				
				$user_data				= array();
				$mate_data				= array();
				
				$user_data_query 		= "select * from users where id=".$challenge['user_id'];
				$user_get_data			= mysqli_query($db, $user_data_query);
				$total_user_get_data	= mysqli_affected_rows($db);
				if($total_user_get_data > 0){
					$user_data	= mysqli_fetch_assoc($user_get_data);
				}
				
				$mate_data_query 		= "select * from users where id=".$challenge['mate_id'];
				$mate_get_data			= mysqli_query($db,  $mate_data_query);
				$total_mate_get_data	= mysqli_affected_rows($db);
				if($total_mate_get_data > 0){
					$mate_data	= mysqli_fetch_assoc($mate_get_data);
				}
				
				$user_notimail			= getUserNotiMialSettings($user_data['id']);
				$mate_notimail			= getUserNotiMialSettings($mate_data['id']);
				$user_content			= "";
				$mate_content			= "";
				//echo $total_user_get_data."<br>".$total_mate_get_data;
				if($total_user_get_data > 0 && $total_mate_get_data > 0){
					//echo "<pre>";
					$user_notimail			= getUserNotiMialSettings($user_data['id']);
					$mate_notimail			= getUserNotiMialSettings($mate_data['id']);
					//echo $user_data['id']."------------------".$winner_user_id."-------------".$mate_data['id'];
					if($user_data['id'] == $winner_user_id){
						
						$link = "<a herf=http://".$_SERVER['HTTP_HOST']."/></a>";
						$user_content	= "Hi ".$user_data['firstname']." ".$user_data['lastname'].",<br/><br/> Congratulations you won the challenge in ".$bitfair_event['name']." against ".$mate_data['firstname']." ".$mate_data['lastname'].". <br/><br/><br/> Thanks MateVMate Team";
						if($mate_notimail === true){
							$mate_content	= "Hi ".$mate_data['firstname']." ".$mate_data['lastname'].",<br/><br/> Sorry you lost the challenge in ".$bitfair_event['name']." against ".$user_data['firstname']." ".$user_data['lastname'].".<br/><br/> Click here to honor your mate ".$link." <br/><br/><br/> Thanks MateVMate Team";
						}
						
						sendNotification($user_data['id'],'wonchallenge',$challenge['id']);
						sendNotification($mate_data['id'],'lostchallenge',$challenge['id']); 
					}else if($mate_data['id'] == $winner_user_id){
						
						$link = "<a herf=http://".$_SERVER['HTTP_HOST']."/></a>";
						
						$mate_content	= "Hi ".$mate_data['firstname']." ".$mate_data['lastname'].",<br/><br/> Congratulations you won the challenge in ".$bitfair_event['name']." against ".$user_data['firstname']." ".$user_data['lastname'].".<br/><br/><br/> Thanks MateVMate Team";
						if($user_notimail === true){
							$user_content	= "Hi ".$user_data['firstname']." ".$user_data['lastname'].",<br/> Sorry you lost the challenge in ".$bitfair_event['name']." against ".$mate_data['firstname']." ".$mate_data['lastname'].". <br/><br/> Click here to honor your mate ".$link." <br/><br/><br/> Thanks MateVMate Team";
						}
						sendNotification($user_data['id'],'lostchallenge',$challenge['id']);
						sendNotification($mate_data['id'],'wonchallenge',$challenge['id']); 
					
					}else{
						$mate_content	= "Hi ".$mate_data['firstname']." ".$mate_data['lastname']."<br/> Results of the challenge in ".$bitfair_event['name']." against ".$user_data['firstname']." ".$user_data['lastname']." is out and there is tie between you and your mate.<br/> Thanks MateVMate Team";
						
						$user_content	=  "Hi ".$user_data['firstname']." ".$mate_data['lastname']."<br/> Results of the challenge in ".$bitfair_event['name']." against ".$user_data['firstname']." ".$mate_data['lastname']." is out and there is tie between you and your mate.<br/> Thanks MateVMate Team";
					}
					
					$subject= "Results of match ".$bitfair_event['name'];
					
					$user_email = $user_data['email'];
					$mate_email = $mate_data['email'];
					
					if($user_content != ""){
						sendEmail($user_email, $subject, $user_content, $cc = '', $bcc = '');
					}
					if($mate_content != ""){
						sendEmail($mate_email, $subject, $mate_content, $cc = '', $bcc = '');
					}
				}
				
				
				//~ if($challenge_status == 'lost'){
					//~ $notification_query= "";
				//~ }
				
				
			}
		}
	}
		
	
}

function sendEmail($to, $subject, $content, $cc = '', $bcc = '')
{
	$rn = "\r\n";
	$boundary = md5(rand());
	$boundary_content = md5(rand());

	// Headers
	$headers  = 'From: MateVMate Support <admin@shinewebservices.com>' . $rn;
	$headers .= 'Mime-Version: 1.0' . $rn;
	$headers .= "Content-type:text/html;charset=UTF-8" . $rn;
	$headers .= 'Reply-To: admin@shinewebservices.com' . $rn;
	$headers .= 'X-Mailer: PHP/' . phpversion();

	//adresses cc and ci
	if ($cc != '') {
		$headers .= 'Cc: ' . $cc . $rn;
	}
	if ($bcc != '') {
		$headers .= 'Bcc: ' . $cc . $rn;
	}
	$headers .= $rn;
	
	
	$msg = $content;
	
	mail($to, $subject, $msg, $headers);
	
	return true;

}



function getWinner(){
	$winner	=rand(1, 5);
	if(($winner % 2) == 0){
		$winning_team='team1_id';
	}else{
		$winning_team='team2_id';
	}
	return $winning_team; 
}

function sendNotification($receiverID, $type, $typeID )
 {
	 if($_SERVER['HTTP_HOST'] == 'localhost') {
			$db = mysqli_connect('192.168.1.77', 'root', 'root', 'matevmate');
		}else {
			$db = mysqli_connect('localhost', 'shineweb_sratool', '[~vc2E-oV*hX', 'shineweb_matevmate');
		}
     $date_created = date('Y-m-d H:i:s');
     $status       = 'unread';
     echo $query = " INSERT INTO notifications (user_id, type_id, type, date_created, status) VALUES ($receiverID, $typeID ,'".$type."', '".$date_created."', '".$status."' )";
     $set_notty             = mysqli_query($db, $query);
     $total_events_query_rows    = mysqli_affected_rows($db);
     if($total_events_query_rows > 0){
         return true;
     }else{
         return false;
     }
    //~ $newNotification                = new Notifications();
    //~ $newNotification->user_id        = $receiverID;
    //~ $newNotification->type            = $type;
    //~ $newNotification->type_id        = $typeID;
    //~ $newNotification->date_created    = date('Y-m-d H:i:s');
    //~ $newNotification->status        = 'unread';
    //~ $notificationSaved                = $newNotification->save();
    //~ return $notificationSaved;
 }
 
function getUserNotiMialSettings($user_id){
	if($_SERVER['HTTP_HOST'] == 'localhost') {
		$db = mysqli_connect('192.168.1.77', 'root', 'root', 'matevmate');
	}else {
		$db = mysqli_connect('localhost', 'shineweb_sratool', '[~vc2E-oV*hX', 'shineweb_matevmate');
	}
	$query 		= "select * from users_notifications_settings where notification_setting_id = 10 and user_id=".$user_id;
	$set_notty	= mysqli_query($db, $query);
	$total_events_query_rows    = mysqli_affected_rows($db);
	if($total_events_query_rows > 0){
		return true;
	}else{
		return false;
	}
}


?>
