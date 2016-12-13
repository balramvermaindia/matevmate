<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenges extends Model
{
    protected $table = 'challenges';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mate_id', 'challenge_status', 'challenge_mode', 'betfair_event_id', 'team_id', 'product_id', 'product_quantity', 'status', 'winner_user_id'
    ];
    
    public function event()
    {
		return $this->belongsTo('App\BetfairEvents', 'betfair_event_id', 'id');
	}
	public function mate()
    {
		return $this->belongsTo('App\User', 'mate_id', 'id');
	}
	public function user()
    {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}
	public function product()
    {
		return $this->belongsTo('App\Products', 'product_id', 'id');
	}
	
	public function getWagerAmountAttribute($value)
    {
		return "AUD ".number_format($value,2);
       
    }
}
