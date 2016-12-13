<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteSports extends Model
{
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','sport_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorite_sports';
    
    public function sport()
    {
		return $this->belongsTo('App\BetfairSports', 'sport_id', 'id');
	}
	
	public function match()
    {
        return $this->hasMany('App\BetfairEvents','betfair_sports_id','sport_id');
    }

}
