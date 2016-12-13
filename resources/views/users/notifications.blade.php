@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">	
	<div class="list_outer outcome_outer yes_bg" style="overflow:hidden; min-height:inherit !important;">
		<h1><span>Notifications</span></h1>
		<div class="list-row search-filter notty-btns">
			<a href="{{ url('notifications/settings') }}" class="btn_blue pull-right notty_settings"><i aria-hidden="true" class="fa fa-cog"></i>Settings</a>
			@if(count($notifications))
				<a style="margin-top:-4px;" href="javascript:void(0)" class="btn_red pull-right danger_btns" id="clear-all"><i aria-hidden="true" class="fa fa-trash"></i>Remove All</a>
			@endif
			<a href="javascript:void(0)" class="btn_red pull-right danger_btns" id="remove" style="display:none; margin-top:-4px;"><i aria-hidden="true" class="fa fa-trash"></i>Remove Selected</a>	
		</div>
<!--
		<div class="profile_title" style="margin:10px">
			<span>Your Notifications</span>
			<a href="{{ url('notifications/settings') }}" class="btn_blue pull-right"><i aria-hidden="true" class="fa fa-cog"></i>Settings</a>
			<a href="javascript:void(0)" class="btn_blue pull-right" id="clear-all"><i aria-hidden="true" class="fa fa-cog"></i>Clear All</a>
		</div>
-->
		<div class="notty-body">
			@if( count($notifications) )
				@foreach( $notifications as $notification )
				
					@if($notification->type == "addmate")
						<?php $materequest_sender = getNotificationDetail($notification->type,$notification->type_id); ?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									@if(@$materequest_sender->photo == "")
										<img src="{{ url('assets/img/cmt.png') }}" style="margin-top:0px !important;">
									@else
										<img src="{{ url('assets/users/img/user_profile/'.@$materequest_sender->photo) }}" style="height:auto; margin-top:0px;">
									@endif
								</div>
								<span>{{ @$materequest_sender->firstname }} {{ @$materequest_sender->lastname }}</span> sent you a mate request.
								<p>
									<i class="fa fa-calendar" aria-hidden="true"></i>
									{{ date("d M, Y", strtotime(@$notification->date_created)) }}
								</p>
							</div>
							<?php $materequest_status = getRequestStatus($notification->type_id, $notification->user_id);  
							?>
							
							@if(@$materequest_status->status == "pending")
								<div class="list-col cmts matchs_btn" style="width:223px;">
									<a class="cmt_btn" href="{{ url('accept/mate/'.$materequest_status->id) }}" >Accept</a>
									<a class="accpet_btn" href="{{ url('reject/mate/'.$materequest_status->id) }}">Decline</a>
								</div>
							@else
								<div class="list-col cmts matchs_btn">
									<a class="cmt_btn" target="_blank" href="{{ url('user-profile/'.$notification->type_id) }}">View Profile</a>
								</div>
							@endif
						</div>
					@endif
					@if($notification->type == "rejectmaterequest")
					<?php $rejectrequest = getNotificationDetail($notification->type,$notification->type_id); ?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									@if(@$rejectrequest->photo == "")
										<img src="{{ url('assets/img/cmt.png') }}" style="margin-top:0px !important;">
									@else
										<img src="{{ url('assets/users/img/user_profile/'.@$rejectrequest->photo) }}" style="height:auto; margin-top:0px !important;">
									@endif
								</div>
								<span>{{ @$rejectrequest->firstname }} {{ @$rejectrequest->lastname }}</span> rejected your mate request.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('user-profile/'.$notification->type_id) }}">View Profile</a>
							</div>
						</div>
					@endif
					@if($notification->type == "acceptmaterequest")
						<?php $acceptmaterequest = getNotificationDetail($notification->type,$notification->type_id); ?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									@if(@$acceptmaterequest->photo == "")
										<img src="{{ url('assets/img/cmt.png') }}" style="margin-top:0px !important;">
									@else
										<img src="{{ url('assets/users/img/user_profile/'.@$acceptmaterequest->photo) }}" style="height:auto; margin-top:0px !important;">
									@endif
								</div>
								<span>{{ @$acceptmaterequest->firstname }} {{ @$acceptmaterequest->lastname }}</span> accepted your mate request.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('user-profile/'.$notification->type_id) }}">View Profile</a>
							</div>
						</div>
					@endif 
					@if($notification->type == "placechallenge")
						<?php $request = getNotificationDetail($notification->type,$notification->type_id); ?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									@if(@$request->user->photo == "")
										<img src="{{ url('assets/img/cmt.png') }}" style="margin-top:0px !important;">
									@else
										<img src="{{ url('assets/users/img/user_profile/'.@$request->user->photo) }}" style="height:auto; margin-top:0px !important;">
									@endif
								</div>
								<span>{{ @$request->user->firstname }} {{ @$request->user->lastname }}</span> placed a challenge against you.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
							</div>
						</div>
					@endif
					@if($notification->type == "acceptchallenge")
						<?php $request = getNotificationDetail($notification->type,$notification->type_id); ?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									@if(@$request->mate->photo == "")
										<img src="{{ url('assets/img/cmt.png') }}" style="margin-top:0px !important;">
									@else
										<img src="{{ url('assets/users/img/user_profile/'.@$request->mate->photo) }}" style="height:auto; margin-top:0px !important;">
									@endif
								</div>
								<span>{{ @$request->mate->firstname }} {{ @$request->mate->lastname }}</span> accepted your challenge request.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
							</div>
						</div>
					@endif
					
					@if($notification->type == "rejectchallenge")
						<?php $request = getNotificationDetail($notification->type,$notification->type_id); ?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									@if(@$request->mate->photo == "")
										<img src="{{ url('assets/img/cmt.png') }}" style="margin-top:0px !important;">
									@else
										<img src="{{ url('assets/users/img/user_profile/'.@$request->mate->photo) }}" style="height:auto; margin-top:0px !important;">
									@endif
								</div>
								<span>{{ @$request->mate->firstname }} {{ @$request->mate->lastname }}</span> rejected your challenge request.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
							</div>
						</div>
					@endif
					

					@if($notification->type == "wonchallenge")
						<?php $request = getNotificationDetail($notification->type,$notification->user_id);
							   $mateProfile = getMateProfileDetail($notification->type_id,$notification->user_id); 	
						 ?>
						
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									<img src="{{ url('assets/img/cmt.png') }}"  style="margin-top:0px !important;">
								</div>
								<b>Congratulations</b>, you won the challenge against {{ ucfirst(@$mateProfile->firstname) }} {{ ucfirst(@$mateProfile->lastname) }}.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
							</div>
						</div>
					@endif 
					
					@if($notification->type == "lostchallenge")
						<?php $request = getNotificationDetail($notification->type,$notification->user_id); 
							  $mateProfile = getMateProfileDetail($notification->type_id,$notification->user_id);	
						?>
						<div class="list-row notification">
							<div class="list-col">
								<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
								<div class="small_profile">
									<img src="{{ url('assets/img/cmt.png') }}"  style="margin-top:0px !important;">
								</div>
								<b>Sorry</b>, you lost the challenge against {{ ucfirst(@$mateProfile->firstname) }} {{ ucfirst(@$mateProfile->lastname) }}.
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
							</div>
							<div class="list-col cmts matchs_btn">
							<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
							</div>
						</div>
					@endif


					@if($notification->type == "honourchallenge")
						<?php $request = getNotificationDetail($notification->type,$notification->type_id); ?>
						@if( $request->honour_status == "cancelled")
							<div class="list-row notification">
								@if($notification->user_id == $request->user_id)
									<div class="list-col">
										<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
										<div class="small_profile">
											@if(@$request->mate->photo == "")
												<img src="{{ url('assets/img/cmt.png') }}"  style="margin-top:0px !important;">
											@else
												<img src="{{ url('assets/users/img/user_profile/'.@$request->mate->photo) }}" style="height:auto; margin-top:0px !important;">
											@endif
										</div>
										<span>{{ @$request->mate->firstname }} {{ @$request->mate->lastname }}</span> cancelled the honour wager.
										<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
									</div>
								@else
									<div class="list-col">
										<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
										<div class="small_profile">
											@if(@$request->user->photo == "")
												<img src="{{ url('assets/img/cmt.png') }}"  style="margin-top:0px !important;">
											@else
												<img src="{{ url('assets/users/img/user_profile/'.@$request->user->photo) }}" style="height:auto; margin-top:0px !important;">
											@endif
										</div>
										<span>{{ @$request->user->firstname }} {{ @$request->user->lastname }}</span> cancelled the honour wager.
										<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
									</div>
								@endif
								<div class="list-col cmts matchs_btn">
									<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
								</div>
							</div>
						@elseif ( $request->honour_status == "completed" )
							<div class="list-row notification">
								
								@if($notification->user_id == $request->user_id)
									<div class="list-col">
										<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
										<div class="small_profile">
											@if(@$request->mate->photo == "")
												<img src="{{ url('assets/img/cmt.png') }}"  style="margin-top:0px !important;">
											@else
												<img src="{{ url('assets/users/img/user_profile/'.@$request->mate->photo) }}" style="height:auto; margin-top:0px!important;">
											@endif
										</div>
										<span>{{ @$request->mate->firstname }} {{ @$request->mate->lastname }}</span> honoured the wager.
										<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
									</div>
								@else
									<div class="list-col">
										<input type="checkbox" name="nottycheck" class="nottycheck" data-id = "{{ $notification->id }}" style="float:left; margin: 9px 5px 9px 0px;">
										<div class="small_profile">
											@if(@$request->user->photo == "")
												<img src="{{ url('assets/img/cmt.png') }}"  style="margin-top:0px !important;">
											@else
												<img src="{{ url('assets/users/img/user_profile/'.@$request->user->photo) }}" style="height:auto; margin-top:0px !important;">
											@endif
										</div>
										<span>{{ @$request->user->firstname }} {{ @$request->user->lastname }}</span> honoured the wager.
										<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ date("d M, Y", strtotime(@$notification->date_created)) }}</p>
									</div>
								@endif
								<div class="list-col cmts matchs_btn">
									<a class="cmt_btn" target="_blank" href="{{ url('challenge-detail/'.$notification->type_id) }}">View Detail</a>
								</div>
							</div>
						@endif
					@endif
				@endforeach
				<div class="notty-body" id="load-notty"></div>
				<div class="challenge_msg no_record_found text-center notty-loader" id="loader" style="display:none;"><img src="{{ url('assets/users/img/pre_loader.gif') }}"/></div>
				<div class="challenge_msg no_record_found text-center notty-loader" style="display:none;" id="no-more-notty"><b>That's all, folks!.</b></div>
			@else
				<div class="mate challenge_msg no_record_found text-center"><span>Sorry! No new notification was found.</span></div>
			@endif

			

		</div>
	</div>
</div>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			
			//unchecked all the checked checkboxes on page load
			$(':checkbox').each(function(checkbox) {
				if( $(this).is(':checked') ){
					$(this).prop('checked', false);
				}
			});
			
			//do clear all notifications of user
			$(document).off('click','#clear-all').on('click','#clear-all',function(e){
				e.preventDefault();
				
				$.confirm({
					text: "Are you sure you want to remove all notifications?",
					title: "Confirmation Required",
					confirm: function(button) {
						$.ajax({
							url	: siteurl + '/clear-all-notifications',
							type: 'GET',
							dataType: 'text',
							success: function( response ) {
								if( response == 1 ) {
									$('.notty-body').html('');
									var html = '<div class="mate challenge_msg no_record_found text-center"><span>Sorry! No new notification was found.</span></div>'
									$('.notty-body').html(html);
								} else {
									generate('error','There was some error while completing the request. Please try again in some time.');
								}
							}
						});
					},
					cancel: function(button) {
					// nothing to do
					},
					confirmButton: "Yes I am",
					cancelButton: "No",
					post: true,
					confirmButtonClass: "btn-danger",
					cancelButtonClass: "btn-default",
					dialogClass: "modal-dialog modal-lg custom-confirm-matevmate", // Bootstrap classes for large modal
					className: "medium"
				});
			});
			
			// to make array of selected notifications
			var notty_arr = [];	
			$(document).off('click','.nottycheck').on('click','.nottycheck', function(e){
				
				var len = $("input:checked").length;;
				if ( len >= 1 ) {
					$('#remove').css('display','block');
				} else {
					$('#remove').css('display','none');
				}
				
				var notty_id = $(this).attr('data-id');
				if( notty_arr.indexOf(notty_id) == -1 ) {
					notty_arr.push( notty_id );
				} else {
					var index = notty_arr.indexOf(notty_id);
					notty_arr.splice(index, 1);
				}
			});
			
			// ajax request to remove selected notifications
			$(document).off('click','#remove').on('click','#remove', function(e){
				e.preventDefault();
				if( notty_arr ) {
					var data 	= {"selectedNotty" : notty_arr,"_token":"{{ csrf_token() }}" };
					$.ajax({
						type	:'POST',
						url		: siteurl+'/remove-notification',
						data	: data,
						success	: function(response){
							if( response == 1 ) {
								final_arr = notty_arr;
								notty_arr = [];
								$(':checkbox').each(function(checkbox) {
									var dataID = $(this).attr('data-id');
									if( jQuery.inArray( dataID, final_arr) !== -1 ){
										var row = $(this).parent().parent();
										 row.animate({right: '-1000px'}, 1000, function() { $(this).hide(); $(this).remove(); });
									} 
									$('#remove').css('display','none');
									
								});
						} else {
							generate('error','There was some error while completing the request. Please try again in some time.');
						}
						}
						
						});
						
				} else{
					return false;
				}
			});
			
			// load more notifications on scroll
			var page_number = 1;
			var loading = false;
			var Nottylen = $('.notification').length;
			if( Nottylen ) {
				$(window).scroll(function() { //detect page scroll
					if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
						
						
						if(loading == false){
							loading = true;  //set loading flag on
							page_number++; //page number increment
							$.ajax({
								url: siteurl+'/notifications/'+ page_number,
								type: 'get',
								dataType: 'text',
								beforeSend: function() {
												$('#loader').show();
											},
								complete: function(){
											$('#loader').hide();
											},
								success: function(response){
									loading = false;
									if( response != "false"){
										$('#load-notty').append(response);
									}else{
										loading = true;
										$('#no-more-notty').css('display','block');
										//~ var html = '<div class="mate challenge_msg no_record_found text-center"><span>Sorry! No new notification was found.</span></div>';
										//~ $('.notty-body').append(html);
									}
									
								}
							});
						}	
					}
				});	
			} else {
				return false;
			}
			
			
			});
	</script>
@endsection
