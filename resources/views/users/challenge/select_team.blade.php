@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
	<div class="list_outer outcome_outer">
		<h1><span>{{ $event->sports->event_name }}</span></h1>
		 <div style="padding:15px 0px;">
			<div class="steps">
				<span class="sel_step"><i class="fa fa-group"></i>Choose Team</span>
				<span class=""><i class="fa fa-user"></i>Select Mate</span>
				<span><i class="fa fa-beer"></i>Pick Wager</span>
				<span><i class="fa fa-file-text"></i>Place Wager</span>
			</div>
			
			<div class="sel-match">
				<p>{{ $event->name }}</p>
				<span><i class="fa fa-calendar"></i> &nbsp;{{ date('d M, Y | H:i A', strtotime($event->startdatetime)) }} </span>
				<a href="javascript:void(0);" class="" id="team1"><i class="fa fa-arrow-right"></i>{{ $event->team1 }}</a>
				<a href="javascript:void(0);" class="" id="team2"><i class="fa fa-arrow-right"></i>{{ $event->team2 }}</a>
				<div class="cmts continue" style="margin:20px;"> <a class="accpet_btn" href="javascript:void(0);">Continue</a></div>
			</div>
		 </div>
	</div>
	
</div>
<style>
	.profile_outer {
		margin-top: 0px !important;
	}
	.list-col a {
    text-align: left;
	}
</style>		
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
