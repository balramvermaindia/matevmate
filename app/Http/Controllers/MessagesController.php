<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use App\User;



class MessagesController extends Controller
{
	public function viewInboxMessages()
	{
		return view();
	}
   
	
}
