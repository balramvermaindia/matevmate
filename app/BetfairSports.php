<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BetfairSports extends Model
{
    protected $table = 'betfair_sports';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'betfair_id', 'event_type', 'event_name', 'sports_class', 'sports_sysname', 'status'
    ];
    
    
}
