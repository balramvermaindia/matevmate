@extends('layouts.app')
@extends('sections.headerSection')

@section('content')
<header>
	<div class="container" style="padding-bottom:20px;">
		<div class="row">
			<div class="col-md-6 banner_txt signup_txt">
				<h1>The World's greatest<br/>
				Social Betting Platform</h1>
				<p>Challenge you mates on your favourite sports. Wager with alcoholic beverages and secure those all important bragging rights!</p>
				<a href="{{ url('how-it-works')}}" class="banner_btn"><i class="fa fa-play"></i>Learn More</a>
				<div class="signup_img">
					<img src="{{ URL::to('assets/img/signup_img.png') }}"/>
				</div>
			</div>
			<div class="signup_outer">
				<div class="join">
					<h1>JOIN NOW!</h1>
					
					<div class="login_social1">
					<a href="{{ $facebookloginUrl }}" id="social_type" data_uri="facebook"><img src="{{ URL::to('assets/img/f_btn.png') }}"/></a>
					<a href="{{ route('social.login', ['google']) }}"><img src="{{ URL::to('assets/img/g_btn.png') }}"/></a>
					<a href="{{ route('social.login', ['twitter']) }}"><img src="{{ URL::to('assets/img/t_btn.png') }}"/></a>
					</div>
					<div class="login_social login_social2">
						<a href="{{ $facebookloginUrl }}" class="f_icon"><i class="fa fa-facebook"></i></a>
						<a href="{{ route('social.login', ['google']) }}" class="g_icon"><i class="fa fa-google-plus"></i></a>
						<a href="{{ route('social.login', ['twitter']) }}" class="t_icon"><i class="fa fa-twitter"></i></a>
					</div>
				
				
				</div>
				<div class="or">
					<p>Or Fill this form</p>
				</div>
				<form class="signup_form" role="form" method="POST" action="{{ url('/register') }}" id="register_form" >
					{{ csrf_field() }}
					<fieldset class="form-group">
						<i class="fa fa-user"></i>
						<input type="text" class="form-control validate[required]" data-errormessage-value-missing="First Name is required" id="firstName" name="firstName" placeholder="First Name" value="{{ @$user_profile['first_name']}}" data-prompt-position="topRight:-60">
					</fieldset>
					<fieldset class="form-group">
						<i class="fa fa-user"></i>
						<input type="text" class="form-control validate[required]" data-errormessage-value-missing="Last Name is required" id="lastName" name="lastName" placeholder="Last Name" value="{{ @$user_profile['last_name']}}" data-prompt-position="topRight:-60">
					</fieldset>
					<fieldset class="form-group">
						<i class="fa fa-envelope"></i>
						<input id="email" type="text" class="form-control validate[required, custom[email], ajax[userEmailCheckCall]]" data-errormessage-value-missing="Email is required" name="email" value="{{ @$user_profile['email'] }}" placeholder="Email Address" data-prompt-position="topRight:-70">
					</fieldset>
					<fieldset class="form-group">
						<i class="fa fa-lock"></i>
						<input class="form-control validate[required]" id="signuppassword" name="password" type="password" placeholder="Password" data-errormessage-value-missing="Password is required!" data-prompt-position="topRight:-60">
					</fieldset>
					<fieldset class="form-group">
						<i class="fa fa-lock"></i>
						<input class="form-control validate[equals[signuppassword]]" data-errormessage-value-missing="Confirm Password is required!" id="confirmPassword" name="confirmPassword" type="password" placeholder="Re-enter Password" data-prompt-position="topRight:-60">
					</fieldset>
					<fieldset class="form-group">
						<div class="checkbox" style="margin:0px;">
						 <label>
						 <input type="checkbox" data-errormessage-value-missing="You must agree with the terms and conditions" class="validate[required]" name="ageAndTerms" id="ageAndTerms"> I am over 18 and agree to the <a href="{{ url('/term-and-conditions') }}" target="_blank">Terms and Conditions</a>.
						 </label>
						 </div>
					</fieldset>
					<input type="hidden" name="social_id" value="{{ @$user_profile['id'] }}"/>
					<input type="hidden" name="social_type" value="{{ @$social_type }}"/>
					<?php
					if( isset($user_profile['email']) ){
						$social_email= 1;
					}else{
						$social_email= 0;
					}
					?>
					<input type="hidden" name="social_email" value="{{ @$social_email }}"/>
					<fieldset class="form-group">
						<button type="submit" class="register">Register Now</button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</header>

@extends('sections.footerSection')
@endsection
@section('script')

<script type="text/javascript"> 
	$(document).ready(function(){
		formId = '#register_form'; 
		$("input").on('click', '', function() {
			var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
			$(inputFieldFormError).fadeOut(150, function() {
				$(this).remove();
			});
		});
		
		$(formId).validationEngine('attach',{
			autoHidePrompt:true, 
			autoHideDelay: 5000,
			
		});
		$("#firstName").focus();
	});
	
</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ar_AR/all.js#xfbml=1&appId=482497055277703";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

</script>
@endsection
