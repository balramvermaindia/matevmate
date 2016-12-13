@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<style>
.wager_type_game {
	display:none;
}
</style>
	<div class="mid_section">
 			  <div class="col-lg-6" style="padding:0px;">
 			  	<div class="chat_outer">
 			  		<div class="chat_heading">
 			  			<h1 class="chat_selector_type_holder" data-wo-filter-type="mate">Banter Board - My Wagers</h1>
 			  			<?php
							if (count($all_mates) > 0 ) {
 			  			?>
 			  			<div class="user_dropdown wager_type_mate" id="wager-select-mate" style="width:150px; margin-top:11px;">
							<?php
								$last_mate 	= reset($all_mates);
								
								if ( count($all_mates) > 0 && $passedMate > 0 && $passedEvent > 0 ) {
									foreach ( $all_mates as $all_mate ) {
										if ( $all_mate->id == $passedMate ) {
											$last_mate 	= $all_mate; 
										}
									}
								}
							?>
 			  				<div class="user selected-mate-data" data-sel-user="{{ $last_mate->id }}">
 			  					@if ( isset($last_mate->photo) && !empty($last_mate->photo) )
									<img class="selected-mate-data-pic default" src="{{ url('assets/users/img/user_profile/'.$last_mate->photo) }}">
								@else
									<img class="selected-mate-data-pic" src="{{ url('assets/img/user.png') }}">
								@endif
 			  				</div>
 			  				<span class="selected-mate-data-name">{{$last_mate->firstname." ".$last_mate->lastname }}</span>
 			  				<div class="sel_drop_down" id="wager-select-mate-dropdown">
								@foreach($all_mates as $mate)
								<a class="mate-selector" href="javascript:void(0);" id="user_{{ $mate->id }}" data-mate-id="{{ $mate->id }}" data-mate-name="{{$mate->firstname." ".$mate->lastname }}" data-mate-pic="<?php echo (isset($mate->photo) && !empty($mate->photo) ? url('assets/users/img/user_profile/'.$mate->photo) : url('assets/img/user.png') ); ?>">
									<div class="user">
										@if ( isset($mate->photo) && !empty($mate->photo) )
											<img src="{{ url('assets/users/img/user_profile/'.$mate->photo) }}">
										@else
											<img src="{{ url('assets/img/user.png') }}">
										@endif
									</div>
									{{$mate->firstname." ".$mate->lastname }}
								</a>
								@endforeach
							</div>
 			  			</div>
						<?php
					}
					?>
					<div class="user_dropdown wager_type_game" id="banter_type_game_events">
						
					</div>
<!--
					<div class="time_container wager_type_game">
						<div class="match_time"> 2 may, 2016 |  7:45pm</div>
					</div>
-->
 			  			<div class="chat_filter" id="wager-select-type" style="width:110px;">
 			  				<div class="selected_wager_type_filter">Mates</div>
 			  				<div class="sel_drop_down" id="wager-select-type-dropdown" style="width:100%">
								<a href="javascript:void(0);" class="wager_select_type_changer" data-wager-filter="mate">Mates</a>
								<a href="javascript:void(0);" class="wager_select_type_changer" data-wager-filter="game">Games</a>
							</div>
 			  			</div>
 			  		</div>
 			  		
 			  		<div class="chat_container wager-chat-matevmate">
						<?php
							if (count($wagers_events_array) > 0) {
						?>
 			  			<select class="wager_sel event-selector-matevmate" onchange="changeEvent(this);">
							@foreach($wagers_events_array as $event)
 			  				<option value="{{$event->id}}">{{$event->name}}</option>
							@endforeach
 			  			</select>
 			  			<?php
						}
 			  			?>
 			  			
 			  			<div class="chat wager-main-chat-matevmate">
							<?php
								if (count($wagers_events_array) == 0 ) {
									echo 'You dont have any events';
									
								} else if (count($wager_banter_discussion) == 0 ) {
									echo 'Start a new discussion with your mates.';
									
								}
							?>
							@if( count($wager_banter_discussion) > 0 )
								@foreach($wager_banter_discussion as $discussion)
								<?php
									$person_class= "other-person";
									if ($discussion->user_id == Auth::user()->id) {
										$person_class= "your-self";
									}
								?>
								<div id="mvmbo_{{ $discussion->id }}" class="person {{$person_class}} wager-only-bentor" data-mate-banter-id="{{ $discussion->id }}">
									@if($discussion->user_id == Auth::user()->id)
										<div class="chat_txt">
											<div class="text_bg">
												<i title="Delete" class="fa fa-remove delete_comment" id="mvmc_<?php echo $discussion->id; ?>"></i>
												{{$discussion->comment}}
											</div>
										</div>
										<div class="person_img">
											<div class="user">
												@if ( isset($discussion->commentBy->photo) && !empty($discussion->commentBy->photo) )
													<img src="{{ url('assets/users/img/user_profile/'.$discussion->commentBy->photo) }}">
												@else
													<img src="{{ url('assets/img/user.png') }}">
												@endif
											</div>
											
	<!--										<div class="chat_time">
												{{$discussion->commentBy->firstname}}
											</div>
	!-->
											<div class="chat_time">
												{{$discussion->created_at->diffForHumans()}}
											</div>
										</div>
									@else
										<div class="person_img">
											<div class="user">
												@if ( isset($discussion->commentBy->photo) && !empty($discussion->commentBy->photo) )
													<img src="{{ url('assets/users/img/user_profile/'.$discussion->commentBy->photo) }}">
												@else
													<img src="{{ url('assets/img/user.png') }}">
												@endif
											</div>
											<!--
											<div class="chat_time">
												{{$discussion->commentBy->firstname}}
											</div>
											!-->
											<div class="chat_time">
												{{$discussion->created_at->diffForHumans()}}
											</div>
										</div>
										<div class="chat_txt">
											<div class="text_bg">
<!--
												<i title="Delete" class="fa fa-remove delete_comment" id="mvmc_<?php echo $discussion->id; ?>"></i>
-->
												{{$discussion->comment}}
											</div>
										</div>
									@endif
								</div>
								@endforeach
							@endif
 			  			</div>
 	 			  	</div>
 	 			  	<div class="comt_outer">
						<input type="text" placeholder="Type your comment" name="bbWager" id="bbWager"/>
						<a id="bbWagerPost" class="bbWagerPost"></a>
 			  		</div>
 			  	</div>
 			  	<input type="hidden" name="_token" id="banter_token" value="{{ csrf_token() }}" />
 			  </div>
 			  <div class="col-lg-6 sport_chat">
 			  
 			  <div class="chat_outer">
 			  		<div class="chat_heading">
 			  			<h1 class="pref_chat_selector_type_holder" data-ps-filter-type="game">Banter Board - My Preferred Sports / Teams</h1>
 			  			
 			  			<a id="pref_challenge_placer" href="javascript:void();" data-pref-sport-event-id="{{ $pref_event_id }}" class="chat_filter place-wager place-wager-pref-sports">Place a Wager</a>
 			  			<div class="chat_filter" style="width:110px;">
 			  				Games
 			  				<div class="sel_drop_down">
								<a href="#">Games</a>
								<a href="#">General Public</a>
							</div>
 			  			</div>
 			  			<?php
							if ( count($preferred_sports_data) > 0 ) {
								$last_pref_event = reset($preferred_sports_data);
						?>
 			  			<div class="user_dropdown wager_type_pref" id="banter_type_game_pref_sports" style="width:210px;">
							
 			  				<span style="display:block;" id="banter_type_pref_first_event" data-event-type-pref-event-id="{{$last_pref_event->betfair_event_id}}">{{$last_pref_event->name}}</span>
	
							<div class="sel_drop_down" id="banter_type_pref_events_dropdown">
								@foreach($preferred_sports_data as $pref_event)
									<a href="javascript:void(0);" data-event-type-pref-event-name="{{$pref_event->name}}" data-event-type-pref-event-id="{{ $pref_event->betfair_event_id }}" data-event-type-pref-event-time="{{date('d M Y | h:i A', strtotime($pref_event->startdatetime))  }}" class="banter_type_pref_events_selector">{{ $pref_event->name }}</a>
								@endforeach
 			  				</div>
 			  				<div class="match_time" id="banter_type_pref_sport_time"><?php echo date('d M Y | h:i A', strtotime($last_pref_event->startdatetime)); ?></div>
							
 			  			</div>
 			  			<?php
						}
						?>
 			  		</div>
 			  		
 			  		<div class="chat_container pref-chat-matevmate">		  			
 			  			<div class="chat pref-main-chat-matevmate">
							<?php
								if (count($preferred_sports_data) == 0 ) {
									echo 'You dont have any events';
									
								} else if (count($pref_banter_discussion) == 0 ) {
									echo 'Start a new discussion with your mates.';
									
								}
							?>	
							@if( count($pref_banter_discussion) > 0 )
								@foreach($pref_banter_discussion as $pref_discussion)
								<?php
									$pref_person_class= "other-person";
									if ($pref_discussion->user_id == Auth::user()->id) {
										$pref_person_class= "your-self";
									}
								?>
								<div id="mvmso_{{ $pref_discussion->id }}" class="person {{$pref_person_class}} pref-only-bentor" data-pef-banter-id="{{ $pref_discussion->id }}">
									@if($pref_discussion->user_id == Auth::user()->id)
										<div class="chat_txt">
											<div class="text_bg">
												<i title="Delete" class="fa fa-remove delete_pref_comment" id="mvmc_<?php echo $pref_discussion->id; ?>"></i>
												{{$pref_discussion->comment}}
											</div>
										</div>
										<div class="person_img">
											<div class="user">
												@if ( isset($pref_discussion->commentBy->photo) && !empty($pref_discussion->commentBy->photo) )
													<img src="{{ url('assets/users/img/user_profile/'.$pref_discussion->commentBy->photo) }}">
												@else
													<img src="{{ url('assets/img/user.png') }}">
												@endif
											</div>
											

											<div class="chat_time">
												{{$pref_discussion->created_at->diffForHumans()}}
											</div>
										</div>
									@else
										<div class="person_img">
											<div class="user">
												@if ( isset($pref_discussion->commentBy->photo) && !empty($pref_discussion->commentBy->photo) )
													<img src="{{ url('assets/users/img/user_profile/'.$pref_discussion->commentBy->photo) }}">
												@else
													<img src="{{ url('assets/img/user.png') }}">
												@endif
											</div>
											<!--
											<div class="chat_time">
												
											</div>
											!-->
											<div class="chat_time">
												{{$pref_discussion->created_at->diffForHumans()}}
											</div>
										</div>
										<div class="chat_txt">
											<div class="text_bg">
<!--
												<i title="Delete" class="fa fa-remove delete_pref_comment" id="mvmc_<?php echo $pref_discussion->id; ?>"></i>
-->
												{{$pref_discussion->comment}}
											</div>
										</div>
									@endif
								</div>
								@endforeach
							@endif
 			  			</div>
 	 			  	</div>
 	 			  	<div class="comt_outer">
 			  			<input type="text" placeholder="Type your comment" name="bbSports" id="bbSports"/>
						<a id="bbSportsPost" class="bbSportsPost"></a>
 			  		</div>
 			  	</div>
 			  <input type="hidden" name="_token" id="sports_token" value="{{ csrf_token() }}" />
 			  </div>

	</div>

@endsection
@extends('sections.usersFooterSection')

@section('script')
<script src="{{ URL::to('assets/js/banter_wager.js') }}"></script>
<script src="{{ URL::to('assets/js/sports_wager.js') }}"></script>

@endsection
