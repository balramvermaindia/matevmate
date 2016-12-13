<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banter extends Model
{
    protected $table = 'banter';
    protected $dates = ['created_at', 'updated_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'sport_id', 'user_id', 'challenge_id', 'comment'
    ];
    
    public function commentBy()
    {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}
}
