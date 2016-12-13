<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class HonourTransactions extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    protected $table = "honour_transactions";

}
