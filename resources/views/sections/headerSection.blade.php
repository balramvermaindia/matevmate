@section('headerSection')
	<!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php
					$currentPath = Route::getFacadeRoot()->current()->uri();

					if ( $currentPath == '/' ) {
				?>
					<a class="navbar-brand" href="#page-top"><img src="{{ URL::to('assets/img/logo1.png') }}"/></a>
				<?php
					} else {
				?>
					<a class="navbar-brand" href="{{ URL::to('/') }}"><img src="{{ URL::to('assets/img/logo1.png') }}"/></a>
				<?php
					}
                ?>
                
            </div>
            
            <div class="bg mobileBack" style="display:none;"></div>
			<div class="extraHeaderLogin" id="hederLogin">
				<a href="#" class="fa fa-close popupcross"></a>
				<p class="errorMessageOuter">Invalid login credentials</p>
				<form class="signup_form login" role="form" method="POST" action="{{ url('/login') }}" id="login_form_outer">
					{{ csrf_field() }}
					<fieldset class="form-group">
						<input type="text" placeholder="Email" name="email" id="loginEmailOuter" class="form-control validate[required, custom[email]]" data-errormessage-value-missing="Email is required"  data-prompt-position="topLeft">
					</fieldset>
					<fieldset class="form-group">
						<input type="password" placeholder="Password" name="password" id="passwordOuter" class="form-control validate[required]" data-errormessage-value-missing="Password is required"  data-prompt-position="topLeft">
					</fieldset>
					<button class="register" type="submit" id="doLoginOuter"><i style="display:none;" id="spin_outer" class="fa fa-spinner fa-spin"></i> LOGIN</button>
					<a href="javascript:void(0);" class="forgotPassword" id="forgotPasswordOuter">Forgot Password?</a>
					<div class="or">
						<p>Or Login with</p>
					</div>
					<div class="login_social">
						<a href="{{ $facebookloginUrl }}" class="f_icon"><i class="fa fa-facebook"></i></a>
						<a href="{{ route('social.login', ['google']) }}" class="g_icon"><i class="fa fa-google-plus"></i></a>
						<a href="{{ route('social.login', ['twitter']) }}" class="t_icon"><i class="fa fa-twitter"></i></a>
					</div>
					
				</form>
				
				<p class="errorMessageFp" id="errorMessagesOuter">There is no user associated with this email</p>
				<form style="display:none;" class="signup_form login" role="form" method="POST" action="{{ url('/password/email') }}" id="fp_form_outer">
					{{ csrf_field() }}
					<fieldset class="form-group">
						<input type="text" placeholder="Email" name="email" id="loginEmailFpOuter" class="form-control validate[required, custom[email]]" data-errormessage-value-missing="Email is required"  data-prompt-position="topLeft">
					</fieldset>
					<button class="register" type="submit" id="sendPasswordLinkOuter"><i style="display:none;" id="spin_inner_fp_outer" class="fa fa-spinner fa-spin"></i> Send Reset Link</button>
					<a href="javascript:void(0);" class="backTologin" id="backTologinOuter">Back to Login</a>
					<div class="or">
						<p>Or Login with</p>
					</div>
					<div class="login_social">
						<a href="{{ $facebookloginUrl }}" class="f_icon"><i class="fa fa-facebook"></i></a>
						<a href="{{ route('social.login', ['google']) }}" class="g_icon"><i class="fa fa-google-plus"></i></a>
						<a href="{{ route('social.login', ['twitter']) }}" class="t_icon"><i class="fa fa-twitter"></i></a>
					</div>
					
				</form>
			</div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll @if( Request::is('/') ) current @endif">
                        <a href="{{ URL::to('/') }}">Home</a>
                    </li>
                    <li class="page-scroll @if( Request::is('/how-it-works') ) current @endif">
                        <a href="{{ URL::to('/how-it-works') }}">How It Works</a>
                    </li>
<!--
                    <li class="page-scroll">
                        <a href="#about">Bet</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#contact">Sports</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#contact">Mates</a>
                    </li> 
                    <li class="page-scroll">
                        <a href="#contact">Shop</a>
                    </li>
-->
                    @if( Auth::user() )
						<li class="btn" id="loginItem mobile-display">
							<a href="{{ url('/dashboard') }}">DASHBOARD</a>
							@include('sections.login')
						</li>  
					@else
						<li class="btn @if( Request::is('register') ) current @endif" >
							<a href="{{ url('/register') }}"><img src="{{ URL::to('assets/img/signup.png') }}"/>SIGN UP</a>
						</li>
						<li class="btn" id="loginItem">
							<a class="login_item" href="javascript:void(0);"><img src="{{ URL::to('assets/img/login.png') }}"/>LOGIN</a>
							@include('sections.login')
						</li>
						
						<li class="btn" id="loginItemOuter">
							<a class="login_item_outer" href="javascript:void(0);"><img src="{{ URL::to('assets/img/login.png') }}"/>LOGIN</a>
							@include('sections.login')
						</li>
					@endif             
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    
@endsection 
