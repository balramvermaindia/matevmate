<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Basic Connection
if( $_SERVER['HTTP_HOST'] == 'localhost' )
{
	global $ssl_crt ;
	global $ssl_key ;
	$ssl_crt 	= "/var/www/betfair/ssl/client-2048.crt";
	$ssl_key 	= "/var/www/betfair/ssl/client-2048.key";
	$db 		= mysqli_connect('192.168.1.77', 'root', 'root', 'matevmate');
} else {
	global $ssl_crt ;
	global $ssl_key ;
	$ssl_crt 	= "/var/www/html/public/cron/ssl/client-2048.crt";
	$ssl_key 	= "/var/www/html/public/cron/ssl/client-2048.key";
	$db 		= mysqli_connect('localhost', 'root','M@t3vM8SaDB', 'matevmate');
}


// Non interactive api Login
function certlogin($appKey,$params)
{
	global $ssl_crt;
	global $ssl_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://identitysso-api.betfair.com/api/certlogin");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSLCERT, $ssl_crt);
    curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Application: ' . $appKey
    ));

    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $response = json_decode(curl_exec($ch));

    if ( isset($response->loginStatus) && $response->loginStatus == "SUCCESS" ) {
        return $response;
    } else {
		$error_info = curl_getinfo($ch);
		echo "<pre>";
		print_r($error_info);
        echo 'Call to api-ng failed: ' . "\n";
        echo  'Response: ' . json_encode($response);
        echo curl_error($ch);
        exit(-1);
    }
}


function sportsApingRequest($appKey, $sessionToken, $operation, $params)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-au.betfair.com/exchange/betting/json-rpc/v1"); // For Australian Exchange Server
    //For other explicitly specify https://api.betfair.com/exchange/betting/json-rpc/v1
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Application: ' . $appKey,
        'X-Authentication: ' . $sessionToken,
        'Accept: application/json',
        'Content-Type: application/json'
    ));

    $postData = '[{ "jsonrpc": "2.0", "method": "SportsAPING/v1.0/' . $operation . '", "params" :' . $params . ', "id": 1}]';

    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    debug('Post Data: ' . $postData);
    $response = json_decode(curl_exec($ch));
    debug('Response: ' . json_encode($response));

    curl_close($ch);
	
	//echo '<pre>'; print_r($response); die;
	
    
    if (isset($response[0]->error)) {
        echo 'Call to api-ng failed: ' . "\n";
        echo 'Response: ' . json_encode($response);
        exit(-1);
    } else {
        return $response;
    }

}

function getMarketBook($appKey, $sessionToken, $marketId)
{
    $params = '{"marketIds":["' . $marketId . '"]}';

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

    return $jsonResponse[0]->result[0];
}

function debug($debugString)
{
    global $DEBUG;
    if ($DEBUG)
        echo $debugString . "\n\n";
}


$query					= "select betfair_events.* from betfair_events join challenges where betfair_events.id = challenges.betfair_event_id and result_declared = 0";

$get_events 				= mysqli_query($db, $query);
$total_events_query_rows	= mysqli_affected_rows($db);
if ($total_events_query_rows > 0) {
	while ($events = mysqli_fetch_assoc($get_events)) {
		$winner_id		= getWinner($events);
		//$winner_id		= $events[$winner];
		if ( $winner_id > 0 ) {
			$update_query	= "update betfair_events set winner_id = ".$winner_id." , result_declared = 1 where id = ".$events['id'];
			mysqli_query($db, $update_query);
		}
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

				$update_running_status_query	= "update betfair_events set running_status = 'completed' where id = ".$betfair_event_id;
				mysqli_query($db, $update_running_status_query);
				
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
						$mate_content	= "Hi ".$mate_data['firstname']." ".$mate_data['lastname']."<br/> Result of the challenge in ".$bitfair_event['name']." against ".$user_data['firstname']." ".$user_data['lastname']." is out and there is tie between you and your mate.<br/> Thanks <br/>MateVMate Team";
						
						$user_content	=  "Hi ".$user_data['firstname']." ".$mate_data['lastname']."<br/> Result of the challenge in ".$bitfair_event['name']." against ".$user_data['firstname']." ".$mate_data['lastname']." is out and there is tie between you and your mate.<br/> Thanks <br/>MateVMate Team";
					}
					
					$subject= "Result of the match - ".$bitfair_event['name'];
					
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



function getWinner($events)
{
	$appKey 		= 'XusErRgNbRVA7b5X';
	$params 		= 'username=rahul@shinewebservices.com&password=shine@2015';
	$loginResponse	= certlogin($appKey, $params);
	$sessionToken 	= $loginResponse->sessionToken;
	$marketID		= $events['marketID'];
	$marketBook		= getMarketBook($appKey, $sessionToken, $marketID);
	$winning_team	= 0;
	
	$status		= $marketBook->status;
	if ( $status == 'CLOSED' ) {
		$runners = $marketBook->runners;
		
		foreach ( $runners as $runner ) {

			if ( $runner->status == 'WINNER' ) {
				$winning_team = $runner->selectionId;
			}
		}
		
	} 
	return $winning_team;

	
	//~ $winner	=rand(1, 5);
	//~ if(($winner % 2) == 0){
		//~ $winning_team='team1_id';
	//~ }else{
		//~ $winning_team='team2_id';
	//~ }
	//~ return $winning_team; 
}

function sendNotification($receiverID, $type, $typeID )
 {
	 if($_SERVER['HTTP_HOST'] == 'localhost') {
			$db = mysqli_connect('192.168.1.77', 'root', 'root', 'matevmate');
		}else {
			$db = mysqli_connect('localhost', 'root','M@t3vM8SaDB', 'matevmate');
		}
     $date_created = date('Y-m-d H:i:s');
     $status       = 'unread';
     $query = " INSERT INTO notifications (user_id, type_id, type, date_created, status) VALUES ($receiverID, $typeID ,'".$type."', '".$date_created."', '".$status."' )";
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
	if( $_SERVER['HTTP_HOST'] == 'localhost' )
	{
		$db 		= mysqli_connect('192.168.1.77', 'root', 'root', 'matevmate');
	} else {
		$db 		= mysqli_connect('localhost', 'root','M@t3vM8SaDB', 'matevmate');
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
