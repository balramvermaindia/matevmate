<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Voucher extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    protected $table = "voucher";
    
     public function user()
    {
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

}
