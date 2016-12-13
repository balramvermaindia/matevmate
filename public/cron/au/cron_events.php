<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Basic Connection
if( $_SERVER['HTTP_HOST'] == 'localhost' )
{
	global $ssl_crt ;
	global $ssl_key ;
	global $log_path ;
	$ssl_crt 	= "/var/www/betfair/ssl/client-2048.crt";
	$ssl_key 	= "/var/www/betfair/ssl/client-2048.key";
	$log_path	= "/var/www/matevmatelive/public/cron/log.txt";
	mysql_connect('192.168.1.77', 'root', 'root');
	mysql_select_db('matevmate');
} else {
	global $ssl_crt ;
	global $ssl_key ;
	global $log_path ;
	$ssl_crt 	= "/var/www/html/public/cron/ssl/client-2048.crt";
	$ssl_key 	= "/var/www/html/public/cron/ssl/client-2048.key";
	$log_path 	= "/var/www/html/public/cron/log.txt";
	mysql_connect('localhost','root','M@t3vM8SaDB');
	mysql_select_db('matevmate');
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

// Get all events(sports)
function getAllEventTypes($appKey, $sessionToken)
{

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listEventTypes', '{"filter":{}}'); // filter ex {"filter":{"eventTypeIds":["1477"]}}
	
    return $jsonResponse[0]->result;

}

// get all competitions of an event type
function getCompetitions($appKey, $sessionToken, $eventTypeId)
{

    $params = '{"filter":{"eventTypeIds":["' . $eventTypeId . '"]}}';
    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listCompetitions', $params);
    return $jsonResponse[0]->result;
}


function extractEventTypeId($allEventTypes)
{
	$eventTypeArr = array();
    foreach ( $allEventTypes as $eventType ) {
        if ( $eventType->eventType->name != '' ) {
			$eventTypeArr[] = $eventType->eventType->id;
        }
        
    }
     return $eventTypeArr;
}


function getEventType($appKey, $sessionToken, $horseRacingEventTypeId)
{
	$params = '{"filter":{"competitionIds":["' . $horseRacingEventTypeId . '"]}}';
    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listEvents', $params);

    return $jsonResponse[0];

}

function getEventDetails($appKey, $sessionToken, $horseRacingEventTypeId)
{
	$params = '{"filter":{"competitionIds":["' . $horseRacingEventTypeId . '"]},
			  "marketTypeCodes":["MATCH_ODDS"],
			  "sort":"FIRST_TO_START",
			  "maxResults":"200",
			  "marketProjection":["RUNNER_METADATA", "EVENT"]}';
    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketCatalogue', $params);

    return $jsonResponse[0]->result;

}

function getMarketCatalogue($appKey, $sessionToken, $horseRacingEventTypeId)
{
	$params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"]},
			  "marketTypeCodes":["MATCH_ODDS"],
			  "sort":"FIRST_TO_START",
			  "maxResults" : 1, 
			  "marketProjection":["RUNNER_DESCRIPTION"]}';
    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketCatalogue', $params);

    return $jsonResponse[0];

}
function getMarketType($appKey, $sessionToken, $horseRacingEventTypeId)
{
	$params = '{"filter":{"eventIds":["' . $horseRacingEventTypeId . '"]}}';
    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketTypes', $params);

    return $jsonResponse[0];

}

function getNextRacingMarket($appKey, $sessionToken, $horseRacingEventTypeId)
{

    $params = '{"filter":{"eventIds":["' . $horseRacingEventTypeId . '"],
              "marketTypeCodes":["MATCH_ODDS"]},
              "sort":"FIRST_TO_START",
              "maxResults":"1",
              "marketProjection":["RUNNER_DESCRIPTION"]}';

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketCatalogue', $params);

    return $jsonResponse[0]->result[0];
}
function getNextMarket($appKey, $sessionToken, $horseRacingEventTypeId)
{

    $params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"],
              "marketCountries":["GB"],
              "marketTypeCodes":["WIN"],
              "marketStartTime":{"from":"' . date('c') . '"}},
              "sort":"FIRST_TO_START",
              "maxResults":"1",
              "marketProjection":["RUNNER_DESCRIPTION"]}';

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketCatalogue', $params);

    return $jsonResponse[0]->result[0];
}


function getVenues($appKey, $sessionToken, $horseRacingEventTypeId)
{

    $params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"]}}';

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listVenues', $params);
    

    return $jsonResponse[0];
}
function getTimeRanges($appKey, $sessionToken, $horseRacingEventTypeId)
{

    $params = '{"filter":{"eventTypeIds":["' . $horseRacingEventTypeId . '"]}}';

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listTimeRanges', $params);
    

    return $jsonResponse[0];
}

function getMarketBook($appKey, $sessionToken, $marketId)
{
    $params = '{"marketIds":["' . $marketId . '"], "priceProjection":{"priceData":["EX_BEST_OFFERS"]}}';

    $jsonResponse = sportsApingRequest($appKey, $sessionToken, 'listMarketBook', $params);

    return $jsonResponse[0]->result[0];
}

function printMarketIdRunnersAndPrices($nextHorseRacingMarket, $marketBook)
{

    function printAvailablePrices($selectionId, $marketBook)
    {

        // Get selection
        foreach ($marketBook->runners as $runner)
            if ($runner->selectionId == $selectionId) break;

        echo "\nAvailable to Back: \n";
        foreach ($runner->ex->availableToBack as $availableToBack)
            echo $availableToBack->size . "@" . $availableToBack->price . " | ";

        echo "\n\nAvailable to Lay: \n";
        foreach ($runner->ex->availableToLay as $availableToLay)
            echo $availableToLay->size . "@" . $availableToLay->price . " | ";

    }


    echo "MarketId: " . $nextHorseRacingMarket->marketId . "\n";
    echo "MarketName: " . $nextHorseRacingMarket->marketName;

    foreach ($nextHorseRacingMarket->runners as $runner) {
        echo "\n\n\n===============================================================================\n";

        echo "SelectionId: " . $runner->selectionId . " RunnerName: " . $runner->runnerName . "\n";
        printAvailablePrices($runner->selectionId, $marketBook);
    }
}

function debug($debugString)
{
    global $DEBUG;
    if ($DEBUG)
        echo $debugString . "\n\n";
}

function createLog($text)
{
	global $log_path;
	$myfile = fopen($log_path, "a+") or die("Unable to open file!");	$txt = $text . "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
}

echo "<pre>";
$sql = 'select * from betfair_competitions where status=1';
$res = mysql_query($sql);
if(mysql_num_rows($res) > 0)
{
	$appKey 		= 'XusErRgNbRVA7b5X';
	$params 		= 'username=rahul@shinewebservices.com&password=shine@2015';
	$loginResponse	= certlogin($appKey, $params);
	$sessionToken 	= $loginResponse->sessionToken;
	while ($competition = mysql_fetch_assoc($res)) 
	{
		$strEventTypes 	= $competition['betfair_id'];
		$events			= getEventDetails($appKey, $sessionToken, $strEventTypes);
		foreach($events as $event)
		{
			$betfair_id = $event->event->id;
			$name = $event->event->name;
			$team1 = $event->runners[0]->runnerName;
			$team1_id = $event->runners[0]->selectionId;
			$team2 = $event->runners[1]->runnerName;
			$team2_id = $event->runners[1]->selectionId;
			$startdatetime = $event->event->openDate;
			$timezone = $event->event->timezone;
			$market_id = $event->marketId;
			$market_name = $event->marketName;
			
			$check_sql = "select * from betfair_events where betfair_id='$betfair_id' and marketID='$market_id'";
			$check_res = mysql_query($check_sql);
			if ($market_name == "Match Odds" || $market_name == "Fight Result" || $market_name == "Moneyline" || $market_name == "Moneyline (Listed)")
			{
				if(mysql_num_rows($check_res) > 0)
				{
					//we already have event so nothing to do
				}
				else
				{
					echo "marketID=$market_id ID=$betfair_id <br>";
					$betfair_sports_id = $competition['betfair_sports_id'];
					$betfair_competition_id = $competition['id'];
					
					$insert = "insert into betfair_events(betfair_sports_id,betfair_competition_id,betfair_id,name,team1,team1_id, team2, team2_id, startdatetime, timezone, status, result_declared, winner_id, created_at, marketID) values('$betfair_sports_id','$betfair_competition_id','$betfair_id','$name','$team1','$team1_id', '$team2', '$team2_id', '$startdatetime', '$timezone', 1, 0, 0, '".date("Y-m-d H:i:s")."', '$market_id')";
					mysql_query($insert) or die($insert);
				}
			}
		}
		sleep(5);
	}
}
//createLog("Event cron (AU) -- ".date("D d M h:i A"));
die("done");
