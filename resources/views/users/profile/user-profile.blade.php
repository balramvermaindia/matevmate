@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="profile_outer" style="margin-top:0px !important;">
		<div class="profile_title">
			<span>
				<div class="cmt_pic" style="position:relative;margin-right:5px; vertical-align:middle;">
						@if ( $profile->photo == "")
							<img src="{{ url('assets/img/cmt.png') }}">
						@else
							<img src="{{ url('assets/users/img/user_profile/'.$profile->photo) }}" id="feedback1" style="width:100%; height:100%;">
						@endif
				</div>
				<div style="display:inline-block; vertical-align:middle;">{{ ucfirst(@$profile->firstname) }} {{ ucfirst(@$profile->lastname) }}</div>
			</span>
			@if ( count($userMates) )
				@if ( $userMates->status == 'active' )
					<a class="btn_red pull-right" style="padding: 6px 8px; margin:10px 0px 0px 0px;" href="javascript:void(0)" onClick= "deleteFunction('{{ url('remove/mate/'.$profile->id) }}','Are you sure you want to remove this mate?','Confirmation Required')" id="removeMate">Remove Mate</a>
				@else
				@endif
			@else
				@if ( $status == 'incomingRequestPending' )
					<a class="btn_green pull-right" style="padding: 6px 8px; margin-top:15px; margin-right:0px;;" href="{{ url('accept/mate/'.$mateRequest->id) }}" id="acceptMate">Accept</a>
					<a class="btn_red pull-right" style="padding: 6px 8px; margin:15px 5px 0px 0px;" href="{{ url('reject/mate/'.$mateRequest->id) }}" id="rejectMate">Reject</a>
				@elseif ( $status == 'outgoingRequestPending' )
					<a class="btn_green pull-right" style="padding: 6px 8px; margin-top:15px; margin-right:0px;" href="javascript:void(0);" id="alreadySent">Request Sent</a>
				@elseif ( $status == 'notAMate' )
					<a class="btn_green pull-right" style="padding: 6px 8px; margin-top:15px; margin-right:0px;" href="{{ url('add/mate/'.$profile->id) }}" id="addMate">Add Mate</a>
				@else
				@endif
			@endif
			<a class="btn_green pull-right" style="padding: 6px 8px; margin-top:10px;" href="{{ url('sports?m='. @$profile->id) }}">Place a Wager</a>
		</div>
 		<div class="row">
			<div class="col-md-6">
				<div class="form-row">
					<i class="fa fa-envelope-o"></i>
					<span style="margin-left:5px;">{{ @$profile->email }}</span>
				</div>
				@if(@$profile->mobile_phone)
					<div class="form-row">
						<i class="fa fa-mobile-phone"></i>
						<span style="margin-left:5px;">{{ @$profile->mobile_phone }}</span>
					</div>
				@endif
				@if(@$profile->phone)
					<div class="form-row">
						<i class="fa fa-phone"></i>
						<span style="margin-left:5px;">{{ @$profile->phone }}</span>
					</div>
				@endif
				@if( !empty($profile->address1) || !empty($profile->address2) || !empty($profile->city) || !empty($profile->state) || !empty($profile->country) || !empty($profile->zipcode) )
					<div class="form-row">
						<i class="fa fa-map-marker"></i>
						<span style="margin-left:5px;">{{ @$profile->address1 }}@if( !empty($profile->address1) && !empty($profile->address2) ), @endif {{ @$profile->address2 }} <br/> {{ @$profile->city }}@if( !empty($profile->city) && !empty($profile->state) ), @endif {{ @$profile->state }} <br/> {{ @$profile->country }}@if( !empty($profile->country) && !empty($profile->zipcode) ), @endif {{ @$profile->zipcode }}</span>
					</div>
				@endif
 			</div>
 			<div class="col-md-6">
				<div class="list_outer outcome_outer">
				<h1><span>Stats</span></h1>
				<center>
					<div id="challengeStatsvvvv" style="margin:12px 0px; overflow:hidden; display:block !important">nbwhfbwjhefbwj</div>
				</center>
				<div class="stats">
					<div></div>
					<span>WON</span>
					<div style="background:#ec8b8b;"></div>
					<span>LOST</span>
					<div style="background:#FD9F19;"></div>
					<span>DRAW</span>
					<p>Total Bets: <b id="total">60</b></p>
				</div>
			</div>
 			</div>
 		</div>
 		
 		<div class="list_outer open_betting no_bg_hover outcome_outer">
		<h1><span>Open Challenges</span></h1>
		@if( count($my_open_challenges)>0 )
			@foreach( $my_open_challenges as $my_open_challenge )
				<div class="list-row" href="{{ url('challenge-detail/'.$my_open_challenge->id) }}">
					<div class="list-col" style="width:27%; vertical-align:top;">
						<span>{{ @$my_open_challenge->event->name }}</span>
						<p>
							<i class="fa fa-calendar" aria-hidden="true"></i>
							{{ showDateTime(@$my_open_challenge->event->startdatetime, @$my_open_challenge->event->timezone, "D d M h:i A") }}
						</p>
					</div>
					<div class="list-col" style="width:25%; vertical-align:top;">
						<span>my wager is on:</span>
						@if( @$my_open_challenge->team_id == @$my_open_challenge->event->team1_id)
							<p class="outcome">
								{{ ucfirst(@$my_open_challenge->event->team1) }} to win this game
							</p>
						@else
							<p class="outcome">
								{{ ucfirst(@$my_open_challenge->event->team2) }} to win this game
							</p>
						@endif
					</div>
					<div class="list-col" style="width:20%; vertical-align:top;">
						<span>Wager made with</span>
						@if($userID == $my_open_challenge->user_id)
							<p class="outcome">{{ ucfirst(@$my_open_challenge->mate->firstname) }} {{ ucfirst(@$my_open_challenge->mate->lastname) }}</p>
						@else
							<p class="outcome">{{ ucfirst(@$my_open_challenge->user->firstname) }} {{ ucfirst(@$my_open_challenge->user->lastname) }}</p>
						@endif
					</div>
					<div class="list-col" style="width:28%; vertical-align:top;">
						<span>Wager at Stake</span><br/>
						<p class="outcome">{{ ucwords(@$my_open_challenge->wager_amount) }}</p>
					</div>
				</div>
			@endforeach
		@else
		<div class="mate challenge_msg" style="text-align:center;"><span>Sorry! No open challenge was found.</span>
		</div>
		@endif
	</div>
 	</div>
 	
</div>
<style>
 	.form-row label {
		width: 108px;
	}
	.open_betting .list-row {
    cursor: default !important;
   
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
				var userID = '<?php echo $profile->id; ?>';
				
				$.ajax({
					url: siteurl+ '/getWonLossStatus/'+userID,
					type: 'GET',
					dataType: 'JSON',
					success: function(response){
						if(response){
							dataProvider = response;
							
							$('#total').html(dataProvider[0]['total']);
							var chart = AmCharts.makeChart('challengeStatsvvvv', {
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
							   "fixedPosition":false
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
