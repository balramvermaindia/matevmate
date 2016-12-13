<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetfairCompetitions extends Model
{
    protected $table = 'betfair_competitions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'betfair_sports_id', 'betfair_id' , 'competition_region', 'status',
    ];
    
    public function events()
    {
        return $this->hasMany('App\BetfairEvents','betfair_competition_id','id');
    }
}
