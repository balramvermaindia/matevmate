@if(count($userMates))
	@foreach( $userMates as $userMate )
		@if(isset($userMate->events))
			@foreach( $userMate->events as $events )

			<div class="cmts">
					<div class="cmt_pic">
						@if ( isset($userMate->mates->photo) && !empty($userMate->mates->photo) )
							<img src="{{ url('assets/users/img/user_profile/'.$userMate->mates->photo) }}">
						@else
							<img src="{{ url('assets/img/user.png') }}">
						@endif
					</div>
					<div class="cmt_detail">
						<h6>{{ @$userMate->mates->firstname }} {{ @$userMate->mates->lastname }}</h6>
						<h1>{{ $events['event']->event->name }}</h1>
						<h6><i class="fa fa-calendar" aria-hidden="true"></i> {{ showDateTime($events['event']->event->startdatetime,$events['event']->event->timezone, "d M, Y") }}</h6>
						<p>{{ $events['winner_team'] }} will win the match.</p>
					</div>
					@if(count($events['dissucion']))
						<div class="total_cmts">
							@foreach( $events['dissucion'] as $discussions )
							<?php
							//echo "<pre>"; print_r($discussions->commentBy->photo); die;
							?>
								<div class="total_cmts_row">
									<div class="small_profile">
										@if( @$discussions->commentBy->id == Auth::user()->id )
											@if ( isset($discussions->commentBy->photo) && !empty($discussions->commentBy->photo) )
												<img src="{{ url('assets/users/img/user_profile/'.$discussions->commentBy->photo) }}">
											@else
												<img src="{{ url('assets/img/user.png') }}">
											@endif
										@else
											@if ( isset($discussions->commentBy->photo) && !empty($discussions->commentBy->photo) )
												<img src="{{ url('assets/users/img/user_profile/'.$discussions->commentBy->photo) }}">
											@else
												<img src="{{ url('assets/img/user.png') }}">
											@endif
										@endif
									</div>
									<h1>{{ @$discussions->commentBy->firstname }} {{ @$discussions->commentBy->lastname }}</h1>
									<p>{{ @$discussions['comment'] }}</p>
								</div>
							@endforeach
						</div>
					@endif
					<div class="cmt_btn_outer right_cmt_btn_container">
						<a href="javascript:void(0);" class="cmt_btn right_banter_cmt_btn">COMMENT</a>
						@if( $events['event']->mate_id != Auth::user()->id ) 
							<a href="{{ url('/make/bet/' . $events['event']->event->id) }}" class="accpet_btn">CHALLENGE</a>
						@endif
					</div>
					<div class="cmt_btn_outer cmt_box_container" style="display:none;">
						<textarea placeholder="Type your comment" class="right_cmt_box"></textarea>
						<button class="post_to_right_banter btn btn-xs btn-success" type="button" data-challenge ="{{ $events['event']->id }}" data-event="{{ $events['event']->event->id }}" data-mate="{{ @$userMate->mates->id }}">Comment</button>
						<button type="button" class="cancel_posting_cmt btn btn-xs btn-danger">Cancel</button>
					</div>
				</div>
			@endforeach
		@endif
	@endforeach
@else
<div class="cmts">
	No Record
</div>
@endif
