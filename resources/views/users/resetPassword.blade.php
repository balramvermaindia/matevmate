@extends('layouts.app')
@extends('sections.headerSection')

@section('content')
<header>
	<div class="container" style="padding-bottom:20px;">
		<div class="row">
			<div class="col-md-12 banner_txt signup_txt text-center" style="text-align:center;">
			
			</div>
			<div class="signup_outer reset_form text-center">
				<div class="join">
					<h1>Reset Password!</h1>
				</div>
				<div class="or">
					
				</div>
				<form class="signup_form " role="form" method="POST" action="{{ url('/reset/password') }}" id="reset_form" >
					{{ csrf_field() }}
					
					<fieldset class="form-group">
						<i class="fa fa-lock"></i>
						<input class="form-control validate[required]" id="signuppassword" name="password" type="password" placeholder="New Password" data-errormessage-value-missing="Password is required!" data-prompt-position="topRight:-60">
					</fieldset>
					<fieldset class="form-group">
						<i class="fa fa-lock"></i>
						<input class="form-control validate[equals[signuppassword]]" data-errormessage-value-missing="Confirm Password is required!" id="confirmPassword" name="confirmPassword" type="password" placeholder="Re-enter Password" data-prompt-position="topRight:-60">
					</fieldset>
					<input type="hidden" name="auth" id="auth" value="<?php echo $token; ?>" />
					<fieldset class="form-group">
						<button type="submit" class="register">Reset</button>
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
		formId = '#reset_form'; 
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
@endsection

