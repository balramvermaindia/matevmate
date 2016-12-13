<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = mysqli_connect('localhost', 'root', 'M@t3vM8SaDB', 'matevmate');
//$db = mysqli_connect('54.206.39.19', 'root', 'M@t3vM8SaDB', 'matevmate');

$query = "SELECT id, name, CONVERT_TZ( startdatetime, timezone,  'UTC' ) utcdate, startdatetime, timezone FROM  `betfair_events` where running_status='pending' HAVING utcdate <= NOW()";

$get_events 				= mysqli_query($db, $query);

$total_events_query_rows	= mysqli_affected_rows($db);
$rows_aff = 0;
if ($total_events_query_rows > 0) {
	while ($events = mysqli_fetch_assoc($get_events)) 
	{
		$id				= $events['id'];
		$update_query	= "update betfair_events set running_status = 'active' where id = ".$id." and  running_status = 'pending'";
		mysqli_query($db, $update_query);

		$update_query1	= "update challenges set challenge_status = 'active' where betfair_event_id = ".$id." and  challenge_status = 'pending'";
		mysqli_query($db, $update_query1);

		$update_query2	= "update challenges set challenge_status = 'cancelled' where betfair_event_id = ".$id." and challenge_status = 'awaiting'";
		mysqli_query($db, $update_query2);

		$rows_aff++;
	}
}
	echo $rows_aff . " records updated.";

?>
