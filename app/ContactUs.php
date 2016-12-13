<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','query'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contact_us';
}
