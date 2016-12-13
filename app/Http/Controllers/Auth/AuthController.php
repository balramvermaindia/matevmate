<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\NotificationsSettings;
use App\UsersNotificationsSettings;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Session;
use Mail;
use Socialite;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
	protected $redirectTo = '/register';
    protected $redirectAfterLogout = '/';
    protected $redirectPath = '/dashboard';
    public $facebookloginUrl = '';
    

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$timezoneData = array();
		$userTimezone = date_default_timezone_get();
        $this->middleware($this->guestMiddleware(), ['except' => 'logout', 'getLogout']);
        include(app_path().'/Libraries/config.php');
		$facebookloginUrl 		= $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));
		$this->facebookloginUrl = $facebookloginUrl;
		
		$ipaddres	  = $_SERVER['REMOTE_ADDR'];
		$timezoneData = json_decode($this->get_http_response($ipaddres), true);

		if ( count($timezoneData) ) {
			if ( isset($timezoneData['status']) && $timezoneData['status'] == 'success' ) {
				$userTimezone = $timezoneData['timezone'];
				setcookie( "mvmutz", $userTimezone, strtotime( '+7 days' ));
			}
		}
		Session::put('userTimeZone', $userTimezone);
		\Config::set('app.timezone', $userTimezone); //set users timezone
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => 'required|max:50',
            'lastName' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'ageAndTerms' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
		$maildata	= array();
		
		if ( isset($data['social_email']) && $data['social_email'] == 1 ) {
			$user_auth_code = NULL;
			$status			= 1;
		} else {
			$user_auth_code = md5($data['social_email'] . str_random(10));
			$status			= 0;
		}
		
		$create = User::create([
			'firstname'   		=> $data['firstName'],
			'lastname' 	  		=> $data['lastName'],
			'email' 	  		=> $data['email'],
			'password' 	  		=> bcrypt($data['password']),
			'social_id'   		=> isset($data['social_id']) ? $data['social_id'] : NULL,
			'social_type' 		=> isset($data['social_type']) ? $data['social_type'] : NULL,
			'status'	 	 	=> $status,
			'user_auth_code' 	=> $user_auth_code,
        ]);
        
        
        if ( isset($data['social_email']) && $data['social_email'] == 1 ){
			if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
				// Authentication passed...
				return redirect()->intended('dashboard');
			}
		} else {
			$link		= url('activate/user/' . $user_auth_code);
			$email		= $data['email'];
			$maildata 	= array('firstname' => $data['firstName'], 'link' => $link);
			
			//~ Mail::send('auth.emails.user_add', $maildata, function($message) use ($email) {
				//~ $message->subject('Welcome to Mate V Mate');
				//~ $message->from('info@shinewebservices.com', 'Mate V Mate');
				//~ $message->to($email, ' Account activation Link.');
			//~ });
			$to      = $email;
			$subject = "Welcome to Mate V Mate";
			$message = '<html><body>';
			$message .= "Hello " . $data['firstName']."<br><br>";
			$message .= "Welcome to MateVMate. The World's Greatest Social Betting Platform. <br>";
			$message .= "Please click <a href=". $link ."> here </a> to activate your account. <br><br><br><br>";
			$message .= "Thanks <br>";
			$message .= "MateVMate";
			$message .= "</body></html>";
			
			$this->sendEmail($to, $subject, $message); 
			
			$this->redirectPath= 'sign_up/thankyou';
			return redirect('sign_up/thankyou');
		}
		
		return $create;
    }
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);
		$credentials['status'] = 1;
		
		
        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
		include(app_path().'/Libraries/config.php');
		$facebook->getLogoutUrl();
		$facebook->destroySession();
		Auth::logout();
        return $this->logout();
    }
    
    /**
     * Overriding default behaviour for registration 
     **/
	public function register(Request $request)
	{
		$validator = $this->validator($request->all());

        if ($validator->fails()) {
			 $this->throwValidationException(
                $request, $validator
            );
        }
        $this->create($request->all());

        return redirect($this->redirectPath());
	}
	/**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        if (property_exists($this, 'registerView')) {
            return view($this->registerView);
        }
        
        include(app_path().'/Libraries/config.php');
		
		if (!$fbuser) {
			$fbuser = null;
			$facebookloginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));
			return view('auth.register',compact('facebookloginUrl'));
		} else {
			$user_profile 	= $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
			
			if ( isset($user_profile['email']) && !empty($user_profile['email']) ) {
				$user 			= User::where('email',$user_profile['email'])->where('social_id',$user_profile['id'])->first();
			} else {
				$user 			= User::where('social_id',$user_profile['id'])->first();
			}
			
			if ( count($user) ) {
				return $this->doAutoLogin($user);
			}
			$facebookloginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));
			
			/*if ( $request->session()->get('social_type') ) {
				$social_type = $request->session()->get('social_type');
				$request->session()->forget('social_type');
			}*/
			$social_type = 'facebook';
			return view('auth.register',compact('user_profile','facebookloginUrl','social_type'));
		}
    }
    
    public function checkUserEmail(Request $request)
    {
		$email 		= $request->fieldValue;
		$arrayToJs  = array();
		
		$this->validate($request, [
			'fieldValue' => 'required',
		]);
		
		$arrayToJs[0]  = $request->fieldId;

		$user	= User::where('email', $email)->first();
			
		if (count($user)) {
			$arrayToJs[1] = false;
			$arrayToJs[2] = "Email already exists";
			echo json_encode($arrayToJs);
			exit;
		} else {
			$arrayToJs[1] = true;
			$arrayToJs[2] = "Good to go!";
			echo json_encode($arrayToJs);
			exit;
		}
	}
	
	public function doUpdateUserSocialType(Request $request)
	{
		$request->session()->put('social_type', $request->social_type);
		if ( $request->session()->get('social_type') ) {
			$status = true;
		} else {
			$status = false;
		}
		echo json_encode($status);
	}
	
	public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    
    public function handleProviderCallback($provider)
    {
		//notice we are not doing any validation, you should do it

        $user = Socialite::driver($provider)->user();
         
        // stroing data to our use table and logging them in
        $data = [
            'name' 	=> $user->getName(),
            'email' => $user->getEmail(),
            'id'    => $user->getId()
        ];
		$name_array = explode(" ",$data['name']);
		$user_profile = [
		'id' 		 	=> $data['id'],
		'first_name' 	=> $name_array[0],
		'last_name' 	=> $name_array[1],
		'email'		 	=> $data['email']
		];
		if ( $provider == "google" ) {
			$social_type = "google";
		} else {
			$social_type = "twitter";
		}
		
		$user = User::where('social_id',$user_profile['id'])->where('social_type',$social_type)->first();
		
		// if status = 0 return to register page with error
		if ( count($user) > 0 && $user->status == 1 ) {
			return $this->doAutoLogin($user);
		} else {
			// Handle user status error
		}
		
		$facebookloginUrl= $this->facebookloginUrl;
		return view('auth.register',compact('user_profile', 'facebookloginUrl', 'social_type'));
    }
    
    public function doAutoLogin($user)
    {
		$userID = $user->id;
		Auth::loginUsingId($userID);
        return redirect('dashboard');
	}
	
	 /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return redirect('/');
    }
    
    protected function validateLogin(Request $request)
    {
		$user		= array();
		$input		= $request->input();
		
		$email		= $input['email'];
		$password	= $input['password'];
		
		$user		= User::where('email', trim($email))->where('status', 1)->first();
		
		if ( !$user ) {
			return 'false';
		}
		
		if ( Hash::check( $input['password'], $user->password ) ) {
			return 'true';
		} else {
			return 'false';
		}
	}
	
	protected function get_http_response( $ipaddress )
	{
		$data 	= array();
		$ch 	= curl_init();
		$url	= "http://ip-api.com/json/" . $ipaddress;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$data = curl_exec($ch);
		
		if ( curl_exec($ch) === false ) {
			return array();
		} else {
			return $data;
		}
		return $data;
	}

}
