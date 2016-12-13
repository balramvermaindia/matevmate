@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
		<div class="profile_outer">
			@include('sections.wagerTabs')
 			<div class="list_outer">
				<form method="GET" action="{{ url('profile/wager-summary') }}" id="filter-form">
 				<h1><span>Wager Summary</span>
					<select id="status-filter" name="filter">
						<option value="all" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'all') echo 'selected=selected'; ?>>All</option>
							<option value="awaiting" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'awaiting') echo 'selected=selected'; ?>>Awaiting Acceptance</option>
							<option value="pending" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'pending') echo 'selected=selected'; ?>>Pending</option>
							<option value="active" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'active') echo 'selected=selected'; ?>>Active</option>
							<option value="won" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'won') echo 'selected=selected'; ?>>Won</option>
							<option value="lost" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'lost') echo 'selected=selected'; ?>>Lost</option>
					</select>
 				</h1>
 				</form>
 				<div class="scroll_outer">
					<div class="scroll">
						@if( count($summary)>0 )
							<div class="list-row list-title">
								<div class="list-col" style="width:25%;">Mate Name</div>
								<div class="list-col" style="width:35%;">Match</div>
								<div class="list-col" style="width:30%;">Wager</div>
								<div class="list-col" style="width:10%;">Status</div>
							</div>
							@foreach( $summary as $sum)
								<div class="list-row">
									@if(Auth::user()->id == $sum->user_id)
										<div class="list-col" style="width:25%;">
											{{ ucfirst(@$sum->mate->firstname) }} {{ ucfirst(@$sum->mate->lastname) }}
										</div>
									@else
										<div class="list-col" style="width:25%;">
										{{ ucfirst(@$sum->user->firstname) }} {{ ucfirst(@$sum->user->lastname) }}
									</div>
									@endif
									<div class="list-col" style="width:35%;">
										@if(@$sum->event->sports->id == '7')
											<img src="{{ url('assets/users/img/p12.png') }}" style="display:inline-block"/>
										@endif
										@if(@$sum->event->sports->id == '9')
											<img src="{{ url('assets/users/img/p14.png') }}" style="display:inline-block"/>
										@endif
										@if(@$sum->event->sports->id == '10')
											<img src="{{ url('assets/users/img/p13.png') }}" style="display:inline-block"/>
										@endif
										<div class="match">
										<span>{{ @$sum->event->name }}</span>
										<p><i aria-hidden="true" class="fa fa-calendar"></i>{{ date("D dM hA", strtotime(@$my_open_challenge->event->startdatetime)) }}</p>
										</div>
									</div>
									<div class="list-col" style="width:30%;">
										<p>{{ @$sum->product->name }}</p>
									</div>
									<?php 
										$status 	= '';
										if($sum->challenge_status == 'awaiting'){
											$status = 'Awaiting Acceptance';
										}else{
											$status = ucfirst($sum->challenge_status);
										}
									?>
									<div class="list-col" style="width:10%;">
										{{ @$status }}
									</div>
								</div>
							@endforeach
						@else
							<div class="mate" style="text-align: center; width:100% !important; margin-top:14px;">Sorry! No record found.</div>
						@endif
					</div>
				</div>					
 			</div>
 		</div>
 	</div>
 	<style>
		.list-col img
		{
			margin-left:0px;
		}
		.list_outer h1 select {
		width: 177px !important;
		}		
 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
	<script>
		$(document).ready(function(){
			$(document).off('change','#status-filter').on('change','#status-filter',function(){
				$('#filter-form').submit();
			});
		});
	</script>
@endsection
