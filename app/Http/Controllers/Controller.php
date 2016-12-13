<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use DB;
use App\Notifications;
use App\UsersNotificationsSettings;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    
    protected function sendEmail($to, $subject, $content, $cc = '', $bcc = '')
    {
		$rn = "\r\n";
        $boundary = md5(rand());
        $boundary_content = md5(rand());

		// Headers
        $headers  = 'From: MateVMate Support <admin@matevmate.com>' . $rn;
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
	
	protected function printQuery()
	{
		$queries = DB::getQueryLog();
		$last_query = end($queries);
		print_r($last_query); die;

	}
	
	protected function sendNotification($receiverID, $type, $typeID )
	{
		$newNotification				= new Notifications();
		$newNotification->user_id		= $receiverID;
		$newNotification->type			= $type;
		$newNotification->type_id		= $typeID;
		$newNotification->date_created	= date('Y-m-d H:i:s');
		$newNotification->status		= 'unread';
		$notificationSaved				= $newNotification->save();
		return $notificationSaved;
	}
	
	protected function checkIfUserWantNotification($userID, $sysName)
	{
		$usersNotificationSettings = UsersNotificationsSettings::with('notifications_settings')->where('user_id', $userID)->get()->toArray();
		$selectSettings = array();
		if ( count($usersNotificationSettings) ) {
			foreach( $usersNotificationSettings as $usersNotificationSetting ) {
				array_push($selectSettings, $usersNotificationSetting['notifications_settings']['sysname']);
			}
		}
		if ( count($selectSettings) ) {
			if ( in_array($sysName, $selectSettings) ) {
				return true;
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	}
}
