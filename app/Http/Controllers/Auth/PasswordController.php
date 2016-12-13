<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Redirect;
use App\User;
use App\ResetPassword;
use Mail;
use Session;
use Hash;
use DB;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
		return redirect('/');
	}
    
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
		$input 	= $request->input();
		$email  = $input['email'];
		
		$userData = User::where('email', $email)->first();

		if (count($userData)) {
			$firstname		= $userData->firstname;
			$user_auth_code = md5($email);
			$link			= url('reset/password/' . $user_auth_code);
			$maildata 		= array('firstname' => $firstname, 'link' => $link);
			
			ResetPassword::create(['email' => $email, 'token' => $user_auth_code ]);
			
			//~ Mail::send('auth.emails.password', $maildata, function($message) use ($email) {
				//~ $message->subject('Password Reset Link');
				//~ $message->from('admin@shinewebservices.com', 'Mate V Mate');
				//~ $message->to($email, ' Password Reset Link.');
			//~ });
			
			$to      = $email;
			$subject = "Password Reset Link";
			$message = "Click here to reset your password: <a href=". $link ."> ". $link ." </a>";
			//~ $headers = 'From: MateVMate Support <admin@shinewebservices.com>' . "\r\n" .
				//~ 'Reply-To: admin@shinewebservices.com' . "\r\n" .
				//~ 'X-Mailer: PHP/' . phpversion();
//~ 
			//~ mail($to, $subject, $message, $headers);
			
			$this->sendEmail($to, $subject, $message); 
			
			return redirect('/reset-password/thankyou');
		} else {
			return redirect('/');
		}
	}
	
	protected function validateResetPassword(Request $request)
    {
		$user		= array();
		$input		= $request->input();
		$email		= $input['email'];

		$user		= User::where('email', trim($email))->first();
		
		if ( !$user ) {
			return 'false';
		} else {
			return 'true';
		}
	}
	
	protected function updatePassword(Request $request)
	{
		$input			= $request->input();
		$user_auth_code = $input['auth'];
		$password 		= $input['password'];
		$user			= array();
		$userData		= array();
		
		if ( isset($user_auth_code) ) {
			$user		= ResetPassword::where('token', trim($user_auth_code))->orderBy('created_at', 'desc')->skip(0)->take(1)->first();
			
			
			if ( count($user) ) {
				$email		= $user->email;
				$update 	= [	'password'    => bcrypt($password) ];
				$status 	= User::where('email', $email)->update($update);
				
				if ( $status ) {
					$userData 	= User::where('email', trim($email))->first();
					$userID 	= $userData->id;
					Auth::loginUsingId($userID);
					return redirect('dashboard');
				} else {
					return redirect('/'); // Show some error message
				}
			} else {
				return redirect('/');
			}
		} else {
			return redirect('/');
		}
	}
}
