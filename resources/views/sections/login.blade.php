<div class="login_outer">
	<p class="errorMessage">Invalid login credentials</p>
	<form class="signup_form login" role="form" method="POST" action="{{ url('/login') }}" id="login_form">
		{{ csrf_field() }}
		<fieldset class="form-group">
			<input type="text" placeholder="Email" name="email" id="loginEmail" class="form-control validate[required, custom[email]]" data-errormessage-value-missing="Email is required"  data-prompt-position="topLeft">
		</fieldset>
		<fieldset class="form-group">
			<input type="password" placeholder="Password" name="password" id="password" class="form-control validate[required]" data-errormessage-value-missing="Password is required"  data-prompt-position="topLeft">
		</fieldset>
		<button class="register" type="submit" id="doLogin"><i style="display:none;" id="spin_inner" class="fa fa-spinner fa-spin"></i> LOGIN</button>
		<a href="javascript:void(0);" class="forgotPassword" id="forgotPassword">Forgot Password?</a>
		<div class="or">
			<p>Or Login with</p>
		</div>
		<div class="login_social">
			<a href="{{ $facebookloginUrl }}" class="f_icon"><i class="fa fa-facebook"></i></a>
			<a href="{{ route('social.login', ['google']) }}" class="g_icon"><i class="fa fa-google-plus"></i></a>
			<a href="{{ route('social.login', ['twitter']) }}" class="t_icon"><i class="fa fa-twitter"></i></a>
		</div>
		
	</form>
	
	<p class="errorMessageFp" id="errorMessages">There is no user associated with this email</p>
	<form style="display:none;" class="signup_form login" role="form" method="POST" action="{{ url('/password/email') }}" id="fp_form">
		{{ csrf_field() }}
		<fieldset class="form-group">
			<input type="text" placeholder="Email" name="email" id="loginEmailFp" class="form-control validate[required, custom[email]]" data-errormessage-value-missing="Email is required"  data-prompt-position="topLeft">
		</fieldset>
		<button class="register" type="submit" id="sendPasswordLink"><i style="display:none;" id="spin_inner_fp" class="fa fa-spinner fa-spin"></i> Send Reset Link</button>
		<a href="javascript:void(0);" class="backTologin" id="backTologin">Back to Login</a>
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

@section('loginscripts')
<script>
 $(document).ready(function(){
	 
	 $("#forgotPassword").on('click', function() {
		 $('.errorMessage').css('display', 'none');
		 $('.errorMessageFp').css('display', 'none');
		 $("#login_form").css('display', 'none');
		 $("#fp_form").css('display', 'block');
	 });
	 
	 $("#backTologin").on('click', function() {
		 $('.errorMessage').css('display', 'none');
		 $('.errorMessageFp').css('display', 'none');
		 $("#login_form").css('display', 'block');
		 $("#fp_form").css('display', 'none');
	 });
	 
	formIdFpInner = '#fp_form'; 
	$("input").on('click', '', function() {
		var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
		$(inputFieldFormError).fadeOut(150, function() {
			$(this).remove();
		});
	});

	$(formIdFpInner).validationEngine('attach',{
		autoHidePrompt:true, 
		autoHideDelay: 5000,
	});
	
	$(document).on('click', '#sendPasswordLink', function(e) {
		e.preventDefault();
		
		var email = $('#loginEmailFp').val();
		
		if ( email != '' ) {
			$('#spin_inner_fp').css('display', 'inline-block');
			$.ajax({
				type: 'POST',
				url: "{{ URL::to('/validate/reset-password') }}",
				data: $('#fp_form').serialize(),
				success: function(response) {
					
					if ( response == 'true' ) {
						$('#errorMessages').css('display', 'none');
						$('#fp_form').submit();
					} else {
						$('#spin_inner_fp').css('display', 'none');
						$('#errorMessages').css('display', 'block');
						return false;
					}
				}
			});
		}
	});
	
	$("#forgotPasswordOuter").on('click', function() {
		 $('.errorMessage').css('display', 'none');
		 $('.errorMessageFp').css('display', 'none');
		 $("#login_form_outer").css('display', 'none');
		 $("#fp_form_outer").css('display', 'block');
	 });
	 
	 $("#backTologinOuter").on('click', function() {
		 $('.errorMessage').css('display', 'none');
		 $('.errorMessageFp').css('display', 'none');
		 $("#login_form_outer").css('display', 'block');
		 $("#fp_form_outer").css('display', 'none');
	 });
	 
	formIdFpOuter = '#fp_form_outer'; 
	$("input").on('click', '', function() {
		var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
		$(inputFieldFormError).fadeOut(150, function() {
			$(this).remove();
		});
	});

	$(formIdFpOuter).validationEngine('attach',{
		autoHidePrompt:true, 
		autoHideDelay: 5000,
	});
	
	$(document).on('click', '#sendPasswordLinkOuter', function(e) {
		e.preventDefault();
		
		var email = $('#loginEmailFpOuter').val();
		
		if ( email != '' ) {
			$('#spin_inner_fp_outer').css('display', 'inline-block');
			$.ajax({
				type: 'POST',
				url: "{{ URL::to('/validate/reset-password') }}",
				data: $('#fp_form_outer').serialize(),
				success: function(response) {
					
					if ( response == 'true' ) {
						$('#errorMessagesOuter').css('display', 'none');
						$('#fp_form_outer').submit();
					} else {
						$('#spin_inner_fp_outer').css('display', 'none');
						$('#errorMessagesOuter').css('display', 'block');
						return false;
					}
				}
			});
		}
	});
	
	 
	loginFormIdInner = '#login_form'; 
	$("input").on('click', '', function() {
		var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
		$(inputFieldFormError).fadeOut(150, function() {
			$(this).remove();
		});
	});
	
	$(loginFormIdInner).validationEngine('attach',{
		autoHidePrompt:true, 
		autoHideDelay: 5000,
		
	});
	
	loginFormIdOuter = '#login_form_outer'; 
	$("input").on('click', '', function() {
		var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
		$(inputFieldFormError).fadeOut(150, function() {
			$(this).remove();
		});
	});
	
	$(loginFormIdOuter).validationEngine('attach',{
		autoHidePrompt:true, 
		autoHideDelay: 5000,
		
	});
	 
	$(document).on('click', '.login_item', function() {
		var currentElement = $('.current');
		//currentElement.removeClass('current');
		curAttr = $('.login_outer').css('display');
		
		if ( curAttr == 'none' ) {
			$(this).addClass('current');
			$(this).addClass('sel_btn');
			$('.login_outer').css('display', 'block');
			$("#loginEmail").focus();
		} else {
			$(this).removeClass('current');
			$(this).removeClass('sel_btn');
			$('.login_outer').css('display', 'none');
			$("#firstName").focus();
		}
	});
	
	$(document).on('click', '.login_item_outer', function() {
		var currentElement = $('.current');
		//currentElement.removeClass('current');
		curAttr = $('.extraHeaderLogin').css('display');
		
		if ( curAttr == 'none' ) {
			$(this).addClass('current');
			$(this).addClass('sel_btn');
			$('.extraHeaderLogin').css('display', 'block');
			$('.mobileBack').css('display', 'block');
			$("#loginEmailOuter").focus();
		} else {
			$(this).removeClass('current');
			$(this).removeClass('sel_btn');
			$('.mobileBack').css('display', 'none');
			$('.extraHeaderLogin').css('display', 'none');
			$("#firstName").focus();
		}
	});
	
	$(document).on('click', '#doLogin', function(e) {
		e.preventDefault();
		
		var email = $('#loginEmail').val();
		var pass  = $('#loginEmail').val();
		
		if ( email != '' && pass != '' ) {
			$('#spin_inner').css('display', 'inline-block');
			$.ajax({
				type: 'POST',
				url: "{{ URL::to('/validate/login') }}",
				data: $('#login_form').serialize(),
				success: function(response) {
					
					if ( response == 'true' ) {
						$('.errorMessage').css('display', 'none');
						$('#login_form').submit();
					} else {
						$('#spin_inner').css('display', 'none');
						$('.errorMessage').css('display', 'block');
						return false;
					}
				}
			});
		}
	});
	
	$(document).on('click', '#doLoginOuter', function(e) {
		e.preventDefault();
		
		var email = $('#loginEmailOuter').val();
		var pass  = $('#passwordOuter').val();
		
		if ( email != '' && pass != '' ) {
			$('#spin_outer').css('display', 'inline-block');
			$.ajax({
				type: 'POST',
				url: "{{ URL::to('/validate/login') }}",
				data: $('#login_form_outer').serialize(),
				success: function(response) {
					
					if ( response == 'true' ) {
						$('.errorMessageOuter').css('display', 'none');
						$('#login_form_outer').submit();
					} else {
						$('#spin_outer').css('display', 'none');
						$('.errorMessageOuter').css('display', 'block');
						return false;
					}
				}
			});
		}
	});
	
	$('body').click(function(evt){
		console.log(evt.target.id);   
		if(evt.target.id == "loginItem")
			return;
			
		if(evt.target.id == "hederLogin")
			return;

		if($(evt.target).closest('#loginItem').length)
			return;   
			          
		if($(evt.target).closest('#hederLogin').length)
			return;             
			
		$('.mobileBack').css('display', 'none');
		$('.login_item').removeClass('sel_btn');
		$('.login_outer').css('display', 'none');
		$('.extraHeaderLogin').css('display', 'none');
		
		///$("#firstName").focus();
	});
	
 });
</script>
@endsection
