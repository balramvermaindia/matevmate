<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersMates extends Model
{
    protected $table = 'users_mates';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mate_id', 'status'
    ];
    
	public function mateProfile()
    {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}
	
	public function challenges()
    {
		return $this->hasMany('App\Challenges', 'user_id', 'mate_id');
	}
	
	public function mates()
    {
		return $this->belongsTo('App\User', 'mate_id', 'id');
	}
	
}
