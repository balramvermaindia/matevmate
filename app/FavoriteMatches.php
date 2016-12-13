<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteMatches extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id','user_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorite_matches';
    
     public function match()
    {
		return $this->belongsTo('App\BetfairEvents', 'event_id', 'id');
	}
}
