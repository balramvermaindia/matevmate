<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Products;

use Validator;
use App\Voucher;
use App\Managers;
use Session;
class MvmShopController extends Controller
{
	public function viewLogin() 
	{
		return view('mvm_shop.login');
	}
	
	public function doLogin(Request $request)
	{
		$rules = [ 
			'username' => 'required',
			'password' => 'required'
			];
		$this->validate($request,$rules);
		
		$username = $request->username;
		$password = md5( $request->password );
		$user     = Managers::where('username',$username)->first();
		if ( $user ) {
			$manager = Managers::where('username',$username)->where('password', $password)->first();
			if( $manager ) {
				$manager_arr 				= array();
				$manager_arr['id'] 			= $manager->id;
				$manager_arr['username'] 	= $username;
				$request->session()->put('manager', $manager_arr);
				return view('mvm_shop.voucher');
			}
		}
		$request->session()->flash('invalid','Invalid Credentials');
		return view('mvm_shop.login');
	}
	
	public function voucherDetails(Request $request)
	{
		$rules = [ 
			'code' => 'required',
			];
		$this->validate($request,$rules);
		$data	 = array();
		$code 	 = $request->code;
		$voucher = Voucher::with('user')->where('voucher_code',$code)->first();
		if( $voucher ) {
			$view = view('mvm_shop.voucher_details',compact('voucher'))->render();
			return $data = array( 'type' => 'success','data' => $view,'msg' => '' );
		} else {
			return $data = array('type' => 'error','data' => '', 'msg' => 'Invalid voucher code');
		}
	}
	
	public function viewVouchers()
	{
		return view('mvm_shop.voucher');
	}
	
	public function voucherRedeemed(Request $request)
	{
		$data		 		= array();
		$voucher_code_id    = $request->id;
		
		if( !$voucher_code_id ) {
			return $data = array('type' => 'error','data' => '', 'msg' => 'Error occur while completing the request. Please try after some time');
		}
		
		if ( $request->session()->has('manager') ) {
			$manager_arr = $request->session()->get('manager');
		} else {
			return $data = array('type' => 'error','data' => '', 'msg' => 'Error occur while completing the request. Please try after some time');
		}
		
		$redemption_manager_id = $manager_arr['id'];
		$update 			   = array();
		$update				   = [
							'redemption_manager_id' => $redemption_manager_id,
							'is_redeemed'			=> 1,
							'redemption_date'       => date('Y-m-d H:i:s')
							];
		$status 			   = Voucher::where('id',$voucher_code_id)->update($update);
		
		if ( $status ) {
			$voucher = Voucher::with('user')->where('id',$voucher_code_id)->first();
			$view = view('mvm_shop.voucher_details',compact('voucher'))->render();
			return $data = array('type' => 'success','data' => $view, 'msg' => 'Request completed successfully');
		} else {
			return $data = array('type' => 'error','data' => '', 'msg' => 'Error occur while completing the request. Please try after some time');
		}	
	}
	
	public function viewHistory(Request $request){
		
		if ( ! $request->session()->has('manager') ) {
			return redirect('mvm');
		} 
		$manager_arr 			= $request->session()->get('manager');
		$redemption_manager_id  = $manager_arr['id'];
		$vouchers 				= Voucher::where('redemption_manager_id',$redemption_manager_id)->orderBy('created_at','desc')->get();
		
		return view('mvm_shop.history',compact('vouchers'));	
	}
	
	public function logout(Request $request){
		if ( ! $request->session()->has('manager') ) {
			return redirect('mvm');
		} 
		$request->session()->forget('manager');
		return redirect('mvm');
		
	}
}
