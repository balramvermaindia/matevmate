
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
			@endif
		



