@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
 		<div class="profile_outer">
			@include('sections.profileTabs')
			<div class="profile_title">
				<span>Change Password </span>
			</div>
			<?php /*
			@if (Session::has('success'))
              <div class="success" style="color:green; margin-left:19%; margin-bottom:1%;">
               {{ Session::get('success') }}
               </div>
               {{ Session::forget('success') }}
            @endif
            @if (Session::has('error'))
              <div class="error_msg" style="color:red; margin-left:19%; margin-bottom:1%;">
               {{ Session::get('error') }}
               </div>
               {{ Session::forget('error') }}
            @endif */
            ?>
			<form method="POST" id="change_password_form" action="{{ url('profile/change-password')}}">
				{{ csrf_field() }}
			<div class="row edit-profile">
				<div class="col-md-8">
					<div class="form-row">
						<label>Current Password:<span style="color:red">*</span></label>
						<div class="info">
							<input type="password" name="password" class="validate[required]" data-errormessage-value-missing="Password is required" data-prompt-position="topLeft:50"/>
						</div>
					</div>
					<div class="form-row">
						<label>New Password:<span style="color:red">*</span></label>
						<div class="info">
							<input type="password" name="new_password" id="new_password" class="validate[required]" data-prompt-position="topLeft:50" data-errormessage-value-missing="New Password is required"/>
						</div>
					</div>
					<div class="form-row">
						<label>Re-enter Password:<span style="color:red">*</span></label>
						<div class="info">
							<input type="password" name="new_password_confirmation" class="validate[equals[new_password]]" data-prompt-position="topLeft:50" data-errormessage-value-missing="Confirm Password is required!"/>
						</div>
					</div>
				</div>
			</div>	
			<div class="form-row edit-profile mobile-padding">
				<label>&nbsp;</label>
				<input type="submit" class="btn_blue " name="submit" value="SUBMIT" style="border:none;"/>
			</div>
			</form>
 		</div>
 	</div>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
	<script type="text/javascript"> 
	$(document).ready(function(){
		formId = '#change_password_form'; 
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
