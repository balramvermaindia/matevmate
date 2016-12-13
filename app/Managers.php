<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Managers extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
			
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $table = "managers";
}
