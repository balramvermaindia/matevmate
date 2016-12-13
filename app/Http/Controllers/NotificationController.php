<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\User;
use App\Notifications;
use App\NotificationsSettings;
use App\UsersNotificationsSettings;
use Hash;
use Session;
use DB;
use Redirect;

class NotificationController extends Controller
{
    public function showNotifications(Request $request, $page_number = '1')
	{
		//echo $page_number; die;
		$notifications		= array();
		$userID				= Auth::user()->id;
		$readAll 			= Notifications::where('user_id', $userID)->where('status', 'unread')->update(['status' => 'read']);
		$notty_per_page 	= config('constants.notty_per_page');
		$starting_position  = (($page_number-1)*$notty_per_page);
		$notifications 		= Notifications::where('user_id', $userID)->groupBy(array('user_id','type','type_id'))->orderBy('date_created', 'desc')->skip($starting_position)->take($notty_per_page)->get();
		//$this->printQuery();
		
		if( $request->ajax() ){
			if( count($notifications) ){
				return view('users.notifications_ajax', compact('notifications'))->render();
			} else {
				return "false";
			}
		}else{
			return view('users.notifications', compact('notifications'));
	    }
	}
	
    public function showNotificationSettings()
	{
		$selectedSettings  = array();
		$availableSettings = array();
		$singleArray	   = array();
		$userID	 		   = Auth::user()->id;
		$availableSettings = NotificationsSettings::where('status', 1)->get();
		$selectedSettings  = UsersNotificationsSettings::where('user_id', $userID)->get()->toArray();
		
		if ( count($selectedSettings) ) {
			foreach ($selectedSettings as $selectedSetting) {
				$singleArray[] = $selectedSetting['notification_setting_id'];
			}
		}

		return view('users.notificationsettings', compact('availableSettings', 'singleArray'));
	}
	
	public function updateNotificationSettings(Request $request)
	{
		$input 			= $request->input();
		$settingsArray	= isset($input['settings']) ? $input['settings'] : array();
		$userID	 		= Auth::user()->id;

		if ( count($settingsArray) ) {
			$saved		= false;
		} else {
			$saved		= true;
		}
		
		UsersNotificationsSettings::where('user_id', $userID)->delete();
		
		if ( count($settingsArray) ) {
			foreach ( $settingsArray as $setting ) {
				$newRequest 								= new UsersNotificationsSettings();
				$newRequest->user_id 						= $userID;
				$newRequest->notification_setting_id 		= $setting;
				$saved										= $newRequest->save();
			}
		}
		
		if ( $saved ) {
			Session::flash('success', 'Settings saved successfully.');
		} else {
			Session::flash('error', 'Some error occured while saving settings. Please try again in some time.');
		}

		return Redirect::back();
	}
	
	public function checkForNewNotification(Request $request)
	{
		$input 	= $request->input();
		$worker	= $input['worker'];
		if ( $worker == 'yes' ) {
			$total			= 0;
			$notifications	= array();
			$userID			= Auth::user()->id;
			$notifications	= Notifications::where('user_id', $userID)->where('status', 'unread')->get()->toArray();
			if ( count($notifications) > 0 ) {
				$total = count($notifications);
			}
			return $total;
		}
	}
	
	public function doClearAllNotty()
	{
		$status 	= 0;
		$userID 	= Auth::user()->id;
		$deleted 	= Notifications::where('user_id', $userID)->delete();
		if( $deleted ) {
			$status = 1;
		}
		return $status;
	}
	
	public function doRemoveSelectedNotty( Request $request)
	{
		$status 			= 0;
		$selected_notty_arr = $request->selectedNotty;
		$userID 			= Auth::user()->id;
		$deleted 			= Notifications::where('user_id', $userID)->whereIn('id',$selected_notty_arr)->delete();
		if( $deleted  ){
			$status = 1;
		} 
		return $status;
	}
}
