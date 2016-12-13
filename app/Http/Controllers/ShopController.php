<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Products;

class ShopController extends Controller
{
    public function getAllProducts(Request $request)
    {
		$filter = $request->index;
		if( $filter ){
			$wager = Products::where('status', 1)->where('name','like','%'.$filter.'%')->orderBy('created_at', 'desc')->paginate(4);
		}else{
			$wager = Products::where('status', 1)->orderBy('created_at', 'desc')->paginate(4);
		}
		return view('users.shop.all_products',compact('wager'));
	}
	
	public function getWagerDetails($id)
	{
		Products::findOrFail($id);
		$wager = Products::where('id',$id)->where('status', 1)->first();
		return view('users.shop.wager_details',compact('wager'));
	}
}
