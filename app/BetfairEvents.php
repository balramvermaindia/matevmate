<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetfairEvents extends Model
{
    protected $table = 'betfair_events';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'betfair_sports_id', 'betfair_competition_id', 'betfair_id', 'name' , 'team1', 'team2', 'team1_id', 'team2_id', 'startdatetime', 'timezone', 'status', 'result_declared', 'winner_id'
    ];
    
    public function sports()
    {
		return $this->belongsTo('App\BetfairSports', 'betfair_sports_id', 'id');
	}
	
	 public function competition()
    {
		return $this->belongsTo('App\BetfairCompetitions', 'betfair_competition_id', 'id');
	}
	 
}
