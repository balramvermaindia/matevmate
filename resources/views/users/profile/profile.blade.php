@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="profile_outer">
		@include('sections.profileTabs')
		<div class="profile_title">
			<span>My Profile</span>
			<a href="{{ url('profile/edit')}}" class="btn_blue pull-right"><i aria-hidden="true" class="fa fa-pencil"></i>Edit</a>
		</div>
 		<div class="row">
			<div class="col-md-6">
				<div class="form-row">
					<iframe name="upload_iframe" src="" style="display:none;"></iframe>
					<form target="upload_iframe" name="image_upload" enctype="multipart/form-data" action="{{ url('profile/change-image')}}" method="POST">
						<div class="cmt_pic" style="position:relative;margin-right:5px;">
							@if ( $profile->photo == "")
								<img src="{{ url('assets/img/cmt.png') }}">
							@else
								<img src="{{ url('assets/users/img/user_profile/'.$profile->photo) }}" id="feedback1" style="width:58px;">
							@endif
						</div>
						<div class="file_outer">
						<input name="file" type="file" id="upload"/>
						<input type="button" class="black_blue" style="margin-top:12px; border:none;" name="change-picture" value="Change Picture"/>
						</div>
						{{ csrf_field() }}
					</form>
				</div>
				<div class="form-row">
					<label>First Name:</label>
					<div class="info">
						<span>{{ @$profile->firstname }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Last Name:</label>
					<div class="info">
						<span>{{ @$profile->lastname }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Email Address:</label>
					<div class="info">
						<span>{{ @$profile->email }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Mobile Phone:</label>
					<div class="info">
						<span>{{ @$profile->mobile_phone }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Telephone:</label>
					<div class="info">
						<span>{{ @$profile->phone }}</span>
					</div>
				</div>
 			</div>
 			<div class="col-md-6">
				<div class="form-row">
					<label>Address Line 1:</label>
					<div class="info">
						<span style="height:50px">{{ @$profile->address1 }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Address Line 2:</label>
					<div class="info">
						<span style="height:50px">{{ @$profile->address2 }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Suburb:</label>
					<div class="info">
						<span>{{ @$profile->city }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>State:</label>
					<div class="info">
						<span>{{ @$profile->state }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Postcode:</label>
					<div class="info">
						<span>{{ @$profile->zipcode }}</span>
					</div>
				</div>
				<div class="form-row">
					<label>Country:</label>
					<div class="info">
						<span>{{ @$profile->country }}</span>
					</div>
 				</div>
 			</div>
 		</div>
 	</div>
</div>
<style>
 	.form-row label {
		width: 108px;
	}
 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
<script>
	$(document).ready(function() {
		$('#upload').on('change', function() {
			this.form.submit();
		});
	});
  </script>
@endsection
