<div class="list_outer open_betting outcome_outer open_chall @if( count($my_open_challenges) > 4) mCSB_1 @endif" style="min-height:inherit; margin:10px 15px;">
		<h1><span>Existing Wagers</span></h1>
		@if( count($my_open_challenges)>0 )
			<div id="openChallenges" style="">
				@foreach( $my_open_challenges as $my_open_challenge )
					<a class="list-row dash-span" href="{{ url('challenge-detail/'.$my_open_challenge->id) }}">
						
						<div class="list-col" style="width:30%; vertical-align:top;">
							
							
							<span>
								<?php $img_url = getSportsImageBySportsID( @$my_open_challenge->event->betfair_sports_id ); ?>
								<img class="sport-icon" style="float:left;margin-top:0px; margin-right:3px;margin-left:-9px;" src="{{ url($img_url) }}">{{ @$my_open_challenge->event->name }}</span>
							<p style="display:block;">
								<i class="fa fa-calendar" aria-hidden="true"></i>{{ showDateTime($my_open_challenge->event->startdatetime, $my_open_challenge->event->timezone, "D d M h:i A") }}
							</p>
						</div>
						<div class="list-col" style="width:25%; vertical-align:top;">
							<span>my wager is on:</span>
							@if( @$my_open_challenge->team_id == @$my_open_challenge->event->team1_id)
								<p class="outcome">{{ ucfirst(@$my_open_challenge->event->team1) }} to win this game</p>
							@else
								<p class="outcome">{{ ucfirst(@$my_open_challenge->event->team2) }} to win this game</p>
							@endif
						</div>
						<div class="list-col" style="width:20%; vertical-align:top;">
							<span>Wager made with</span>
							@if(Auth::user()->id == $my_open_challenge->user_id)
								<p class="outcome">{{ ucfirst(@$my_open_challenge->mate->firstname) }} {{ ucfirst(@$my_open_challenge->mate->lastname) }}</p>
							@else
								<p class="outcome">{{ ucfirst(@$my_open_challenge->user->firstname) }} {{ ucfirst(@$my_open_challenge->user->lastname) }}</p>
							@endif
						</div>
						<div class="list-col" style="width:26%; vertical-align:top;">
							<span>Wager at Stake</span>
							<p class="outcome">{{ ucwords(@$my_open_challenge->product->name) }}</p>
						</div>
					</a>
				@endforeach
			</div>
		@else
		<div class="mate challenge_msg no_record_found text-center"><span>Sorry! No record was found.</span>
<!--
		<a class="accpet_btn" style="visibility:visible;background:#60ab59;" href="{{ url('sports') }}">Create New Challenge</a>
-->
		</div>
		@endif
	</div>
