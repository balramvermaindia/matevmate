@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
 		<div class="profile_outer">
			@include('sections.profileTabs')
			
			<div class="profile_title">
				<span>Edit Profile</span>
			</div>
 			<div class="row">
				<div class="col-md-6">
					<div class="form-row">
					<iframe name="upload_iframe" src="" style="display:none;"></iframe>
					<form target="upload_iframe" name="image_upload" enctype="multipart/form-data" action="{{ url('profile/change-image')}}" method="POST">
<!--
						<div class="cmt_pic" style="position:relative;margin-right:5px;">
							@if ( $profile->photo == "")
								<img src="{{ url('assets/img/cmt.png') }}">
							@else
								<img src="{{ url('assets/users/img/user_profile/'.$profile->photo) }}" id="feedback1" style="width:58px;" >
							@endif
						</div>
						<div class="file_outer">
						<input name="file" type="file" id="upload"/>
						<input type="button" class="black_blue" style="margin-top:12px; border:none;" name="change-picture" value="Change Picture"/>
						</div>
-->
						{{ csrf_field() }}
					</form>
				</div>
					<form id="profile_form" method="POST" action="{{ url('profile/edit') }}">
						{{ csrf_field() }}
						<div class="form-row">
							<label>First Name:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" name="firstname" class="validate[required]" data-errormessage-value-missing="First Name is required" value="{{ @$profile->firstname }}" data-prompt-position="topLeft:50"/>
							</div>
						</div>
						<div class="form-row">
							<label>Last Name:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" name="lastname" class="validate[required]" data-errormessage-value-missing="Last Name is required" data-prompt-position="topLeft:50" value="{{ @$profile->lastname }}"/>
							</div>
						</div>
						<div class="form-row">
							<label>Email Address:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" id="userEmail" name="email" class="validate[required, custom[email], ajax[userProfileEditEmailCheckCall]]" data-errormessage-value-missing="Email is required" value="{{ @$profile->email }}" data-prompt-position="topLeft:50"/>
							</div>
						</div>
						<div class="form-row">
							<label>Mobile Phone:</label>
							<div class="info">
								<input type="text" name="mobile_phone" value="{{ @$profile->mobile_phone }}"/>
							</div>
						</div>
						<div class="form-row">
							<label>Telephone:</label>
							<div class="info">
								<input type="text" name="phone" value="{{ @$profile->phone }}"/>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-row">
							<label>Address Line 1:</label>
							<div class="info">
								<textarea style="height:50px; resize:none" name="address1">{{ @$profile->address1 }}</textarea>
							</div>
						</div>
						<div class="form-row">
							<label>Address Line 2:</label>
							<div class="info">
								<textarea style="height:50px; resize:none" name="address2">{{ @$profile->address2 }}</textarea>
							</div>
						</div>
						<div class="form-row">
							<label>Suburb:</label>
							<div class="info">
								<input type="text" name="city" value="{{ @$profile->city }}"/>
							</div>
						</div>
						<div class="form-row">
							<label>State:</label>
							<div class="info">
								<input type="text" name="state" value="{{ @$profile->state }}"/>
							</div>
						</div>
						<div class="form-row">
							<label>Postcode:</label>
							<div class="info">
								<input type="text" name="zipcode" value="{{ @$profile->zipcode }}"/> 							
							</div>
						</div>
						<div class="form-row">
							<label>Country:</label>
							<div class="info">
								<input type="text" name="country" value="{{ @$profile->country }}"/>
							</div>
						</div>
					</div>
				</div>	
				<div class="form-row mobile-padding">
					<label>&nbsp;</label>
					<input type="submit" class="btn_blue" name="submit" value="SUBMIT" style="border:none; margin-right:2px; line-height:17px;"/>
					<a class="black_blue" href="{{ url('profile') }}">CANCEL</a>
				</div>
			</form>
 		</div>
 	</div>
 	<style>
 	.form-row label {
		width: 113px;
	}
 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
<script type="text/javascript"> 
$(document).ready(function(){
	formId = '#profile_form'; 
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
});

</script>
<script>
$(document).ready(function() {
	$('#upload').on('change', function() {
	//	this.form.submit();
	});
});
</script>
@endsection
