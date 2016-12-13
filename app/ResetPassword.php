<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';
    
    public function setUpdatedAtAttribute($value)
	{
		// to Disable updated_at
	}

}
