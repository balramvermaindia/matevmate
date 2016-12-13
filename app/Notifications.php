<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'type_id', 'notification', 'date_created', 'status'
    ];
    
    
}
