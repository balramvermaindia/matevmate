<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MeatTrayRaffleController extends Controller
{
    public function getMeatTrayRaffle()
    {
		return view('users.meat.meat_tray_raffle');
	}
	
	public function getPastTickets()
	{
		return view('users.meat.past_tickets');
	}
}
