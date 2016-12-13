<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationsSettings extends Model
{
    protected $table = 'notifications_settings';
    
    public function users_notifications_settings()
	{
		return $this->hasMany('App\UsersNotificationsSettings','notification_setting_id', 'id');
	}
 
}
