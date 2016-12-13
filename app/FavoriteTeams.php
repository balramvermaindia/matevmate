<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteTeams extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','user_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorite_teams';
}
