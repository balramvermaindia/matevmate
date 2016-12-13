<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use Session;
use App\ContactUs;
use Mail;
use Validator;

class HomeController extends Controller
{
	
	public $facebookloginUrl = '';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        include(app_path().'/Libraries/config.php');
		$facebookloginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));
		$this->facebookloginUrl = $facebookloginUrl;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$facebookloginUrl = $this->facebookloginUrl;
        return view('welcome', compact('facebookloginUrl'));
    }
    
    public function howItWorks()
    {
		$facebookloginUrl = $this->facebookloginUrl;
        return view('howitworks', compact('facebookloginUrl'));
	}
	
	public function activateUser($token)
    {
		if( $token ) {
			$user = User::where('user_auth_code',$token)->first();
			if( count($user) ) {
				User::where('user_auth_code', $token)->where('social_id',$user->social_id)->update(['status' => 1]);
				Session::put('success','Your account has been successfully activated. Please proceed to Login');
			} else {
				Session::put('error','Oops! There is some error while activating the account. Please try later.');
			}
		}
		$facebookloginUrl = $this->facebookloginUrl;
        return view('welcome', compact('facebookloginUrl'));
	}
	
	public function contactUs(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'yourName' => 'required',
            'emailAddress' => 'required',
            'user_query' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
		
		$contact 	= ContactUs::create(['name' => $request->yourName, 'email' => $request->emailAddress, 'query' => $request->user_query ]);
		
		$email		= 'sanjolly@shinewebservices.com'; // Set email here
		$maildata 	= array('enquiry' => $request->user_query, 'name' => $request->yourName, 'email' => $request->emailAddress);
			
		Mail::send('auth.emails.contact_us', $maildata, function($message) use ($email) {
			$message->subject('New Contact Us Enquiry');
			$message->from('info@shinewebservices.com', 'Mate V Mate');
			$message->to($email, 'Contact Us');
		});
		
		Session::put('contact_success','Thanks for sending your message! We will get back to you shortly.');
		return back();
	}
	
	public function getThankYou()
	{
		$facebookloginUrl = $this->facebookloginUrl;
		return view('thankYou',compact('facebookloginUrl'));
	}
	
	public function passwordThankYou()
	{
		$facebookloginUrl = $this->facebookloginUrl;
		return view('thankYouReset', compact('facebookloginUrl'));
	}
	
	public function resetPassword($token)
    {
		$facebookloginUrl = $this->facebookloginUrl;
		if ( $token ) {
			return view('users.resetPassword', compact('token', 'facebookloginUrl'));
		} else {
			return redirect('/');
		}
	}
	
	public function viewTermAndConditions()
	{
		$facebookloginUrl = $this->facebookloginUrl;
		return view('termAndConditions',compact('facebookloginUrl'));
	}
}
