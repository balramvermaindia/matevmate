@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 			
	<div class="list_outer open_betting outcome_outer open_chall @if( count($my_open_challenges) > 4) mCSB_1 @endif">
		<h1><span>My Open Challenges</span></h1>
		@if( count($my_open_challenges)>0 )
			<div id="openChallenges" style="@if( count($my_open_challenges) > 4) height:300px; @endif ">
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
							@if(Auth::user()->id == @$my_open_challenge->user_id )
								@if( @$my_open_challenge->team_id == @$my_open_challenge->event->team1_id)
									<p class="outcome">{{ ucfirst(@$my_open_challenge->event->team1) }} to win this game</p>
								@else
									<p class="outcome">{{ ucfirst(@$my_open_challenge->event->team2) }} to win this game</p>
								@endif
							@else
								@if( @$my_open_challenge->team_id == @$my_open_challenge->event->team1_id)
									<p class="outcome">{{ ucfirst(@$my_open_challenge->event->team2) }} to win this game</p>
								@else
									<p class="outcome">{{ ucfirst(@$my_open_challenge->event->team1) }} to win this game</p>
								@endif
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
							<p class="outcome">{{ ucwords(@$my_open_challenge->wager_amount) }}</p>
						</div>
					</a>
				@endforeach
			</div>
		@else
		<div class="mate challenge_msg no_record_found"><span>Sorry! No open challenge was found.</span>
		<a class="accpet_btn" style="visibility:visible;background:#60ab59;" href="{{ url('sports') }}">Create New Challenge</a>
		</div>
		@endif
	</div>
	
	<div class="sub-section">
		<div class="list_outer outcome_outer yes_bg @if( count($upcoming_sporting_events) > 3) mCSB_2 @endif">
			<h1><span>Upcoming Sporting Events</span></h1>
			<div id="upcomingSportingEvents" style="height:239px;">
				@if( count($upcoming_sporting_events) )
					@foreach( $upcoming_sporting_events as $event)
						<div class="list-row">
							<div class="list-col" style="width:40px;padding:0px;">
								<?php $img_url = getSportsImageBySportsID( @$event->betfair_sports_id ); ?>
								<img src='{{ url("$img_url") }}'/>
							</div>
							<div class="list-col" style="padding-left:0px;">
								<span>{{ @$event->name }}</span>
								<p><i class="fa fa-calendar" aria-hidden="true"></i>{{ showDateTime($event->startdatetime, $event->timezone,"D d M h:i A") }}</p>
							</div>
							<div class="list-col" style="width:120px;">
								<a class="cmt_btn" href="{{ url('make/bet/'.$event->id) }}">Bet Now</a>
								<a class="accpet_btn" href="{{ url('/banter-board') }}">Banter Board</a>
							</div>
						</div>
				  @endforeach
			 @else
				<div class="mate challenge_msg no_record_found"><span>Sorry! No upcoming sporting event was found.</span></div>
			 @endif
			</div>
		</div>
	</div>
		
		<div class="sub-section pull-right">
			<div class="list_outer outcome_outer">
				<h1><span>My Stats</span></h1>
				<center><div id="challengeStats"></div></center>
				<div class="stats">
					<div></div>
					<span>WON</span>
					<div style="background:#ec8b8b;"></div>
					<span>LOST</span>
<!--
					<div style="background:#FD9F19;"></div>
					<span>DRAW</span>
-->
					<p>Total Bets: <b id="total"></b></p>
				</div>
			</div>
		</div>
	

<!--
	<div class="logos_outer">
		<center><img src="{{ url('assets/img/logos.png') }}"/></center>
	</div>
-->
</div>
<style>
		.list_outer a.list-row:hover{
	background:#dbedfb;
	cursor:pointer;
}
#challengeStats {
	width		: 100%;
	height		: 184px;
	font-size	: 11px;
}
<!--
.dash-span span{
	display:block !important;
}
-->
<!--
.amcharts-chart-div a {
	display:none !important;
}
-->
#mCSB_2_container, #mCSB_1_container{
	overflow:visible !important;
	
	
}


	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
@section('script')
	<script type="text/javascript">
		(function($){
			$(window).load(function(){
				var dataProvider = {};
				var userID = '<?php echo Auth::user()->id; ?>';
				$("#openChallenges").mCustomScrollbar(
					{
						theme:"dark-3",
						scrollButtons:{enable:true},
						scrollInertia:400
					}
				);
				
				$("#upcomingSportingEvents").mCustomScrollbar(
					{
						theme:"dark-3",
						scrollButtons:{enable:true},
						scrollInertia:400
					}
				);
				
				$.ajax({
					url: siteurl+ '/getWonLossStatus/'+userID,
					type: 'GET',
					dataType: 'JSON',
					success: function(response){
						if(response){
							dataProvider = response;
							
							$('#total').html(dataProvider[0]['total']);
							var chart = AmCharts.makeChart('challengeStats', {
							  "type": "pie",
							  "theme": "none",
							  "dataProvider": dataProvider,
							  "valueField": "count",
							  "titleField": "status",
							  "colorField": "color",
							  "startDuration":0,
							  //"labelsEnabled": false,
							  "labelText":"[[percents]]%",
							  "radius":70,
							   "balloon":{
							   "fixedPosition":true
							  },
							  "export": {
								"enabled": true
							  }
							} );
						}
					}
				});
			});
			
			
			
		})(jQuery);
	</script>
@endsection
