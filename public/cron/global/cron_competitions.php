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
    curl_setopt($ch, CURLOPT_URL, "https://api.betfair.com/exchange/betting/json-rpc/v1");
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
	$myfile = fopen($log_path, "a+") or die("Unable to open file!");
	$txt = $text . "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
}

echo "<pre>";
$sql = 'select * from betfair_sports where status=1 and event_type="sport"';
$res = mysql_query($sql);
if(mysql_num_rows($res) > 0)
{
	$appKey 		= 'XusErRgNbRVA7b5X';
	$params 		= 'username=rahul@shinewebservices.com&password=shine@2015';
	$loginResponse	= certlogin($appKey, $params);
	$sessionToken 	= $loginResponse->sessionToken;
	while ($sport = mysql_fetch_assoc($res)) 
	{
		$strEventTypes 	= $sport['betfair_id'];
		$competitions	= getCompetitions($appKey, $sessionToken, $strEventTypes);
		foreach($competitions as $compdata)
		{
			$name = $compdata->competition->name;
			$betfair_sports_id = $sport['id'];
			$betfair_id = $compdata->competition->id;
			$competition_region = $compdata->competitionRegion;
			
			$check_sql = "select * from betfair_competitions where betfair_id='$betfair_id'";
			$check_res = mysql_query($check_sql);
			
			if(mysql_num_rows($check_res) > 0)
			{
				//we already have competition so nothing to do
			} else {
				$insert = "insert into betfair_competitions(name,betfair_sports_id,betfair_id,competition_region) values(
				'$name','$betfair_sports_id','$betfair_id','$competition_region')";
				mysql_query($insert);
			}
		}
	}
}
//createLog("Competition cron (Global) -- ".date("D d M h:i A"));
die("done");
