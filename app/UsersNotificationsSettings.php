<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersNotificationsSettings extends Model
{
    protected $table = 'users_notifications_settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ntification_setting_id', 'value'
    ];
    
    public function notifications_settings()
	{
		return $this->belongsTo('App\NotificationsSettings','notification_setting_id', 'id');
	}

}
