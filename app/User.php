<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'gender', 'dob', 'photo', 'phone','mobile_phone', 'address1', 'address2', 'city', 'state', 'country', 'zipcode', 'status', 'social_id', 'social_type', 'user_auth_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
