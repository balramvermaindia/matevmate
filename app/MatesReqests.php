<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatesReqests extends Model
{
	protected $table = 'mates_requests';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mate_id', 'status', 'date_sent', 'date_accepted'
    ];
    
    public function mateProfile()
    {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}
	
	public function sentRequestMateProfile()
    {
		return $this->belongsTo('App\User', 'mate_id', 'id');
	}
}
