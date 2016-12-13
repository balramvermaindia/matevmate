@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
		<div class="profile_outer">
			@include('sections.wagerTabs')
			<div class="@if(count($open_public_wagers)>0) list_outer @endif open_betting my-wager open-public-wagers">
			@if( count($open_public_wagers)>0 )
				@foreach( $open_public_wagers as $open_public_wager )
					<div class="list-row" href="{{ url('challenge-detail/'.$open_public_wager->id) }}" style="border:none;background:none;">
						
							<?php $img_url = getSportsImageBySportsID( @$my_open_challenge->event->betfair_sports_id ); ?>
							<div class="list-col" style="width:50%;">
								<span style="display:block;"><a href="{{ url('sports/event/'.@$open_public_wager->event->id) }}" target="_blank"><img src="{{ url($img_url) }}" style="float: left; height: 20px; margin-left: -11px; margin-top: 0;"/>{{ @$open_public_wager->event->name }}</a></span>
								<p>
									<i class="fa fa-calendar" aria-hidden="true"></i>
									{{ showDateTime(@$open_public_wager->event->startdatetime,@$open_public_wager->event->timezone, "D d M h:i A") }}
								</p>
							</div>
							<div class="list-col" style="width:50%;">
								<span style="display:block;">my wager is on:</span>
								@if( @$open_public_wager->team_id == @$open_public_wager->event->team1_id)
									<p class="outcome">
										{{ ucfirst(@$open_public_wager->event->team1) }} to win this game
									</p>
								@else
									<p class="outcome">
										{{ ucfirst(@$open_public_wager->event->team2) }} to win this game
									</p>
								@endif
							</div>
						
						</div>
						<div class="list-row" style="border:none;background:none;">
						<div class="list-col" style="width:50%;">
							
							
								<span style="display:block;">Wager at Stake</span>
								<p class="outcome">{{ ucwords(@$open_public_wager->wager_amount) }}</p>
							</div>
							
							<div class="list-col" style="width:50%;">
								<span style="display:block;">Challenger</span>							
								<p class="outcome"><a href="{{ url('user-profile/'.@$open_public_wager->user->id) }}" target="_blank" style="text-decoration:underline;">{{ ucfirst(@$open_public_wager->user->firstname) }} {{ ucfirst(@$open_public_wager->user->lastname) }}</a></p>
							</div>
							
						
						@if(Auth::user()->id != @$open_public_wager->user_id)
							<a class="accpet_btn" href="{{ url('wagers/open-public-wagers/accept/'.$open_public_wager->id) }}">ACCEPT</a>
						@endif
					</div>
				@endforeach
			@else
			<div class="mate" style="text-align: center; width:100% !important; margin-top:10px;">Sorry! No record found.</div>
			@endif
		</div>
	</div>

	</div>
	<style>
		.list_outer a.list-row:hover{
	background:#dbedfb;
	cursor:pointer;
}
	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')


