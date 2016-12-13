@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="list_outer outcome_outer yes_bg" style="min-height:inherit;">
		<h1>
			<div class="challenge_continue">
				<span>{{ @$competition_name }}</span>
			</div>
			<?php 
			
				if ( isset($passedMateID) && $passedMateID > 0 ) {
					$concatStr = '?m='.$passedMateID;
					$mateID = $passedMateID;
				} else {
					$concatStr = '';
					$mateID = '';
				}
			
				if( @$sport->event_type == "sport" ){
					$url = url('sports/'.@$sport->sports_sysname.$concatStr);
				} else {
					$url = url('sports'.$concatStr);
					
				}
			?>
			
			<div class="challenge_continue">
				<a class="pull-right btn accpet_btn" href="{{ $url }}" style="color:#fff; margin:5px;font-family:ubuntumedium; font-size: 14px;padding: 6px 15px;width: auto;">Back</a>
			</div>
		</h1>
		<div class="list-row search-filter">
			<form method="GET" action="{{ url('sports/competition/'.$competition_id) }}" id ="search_form">
				<div class="search" style="margin-top:5px;"><input type="text" placeholder="Search a team" name="filter" value="<?php if(!empty($_GET['filter'])) echo $_GET['filter']; ?>"></div>
				<div style="width:40%;" class="search">
					<a class="more_filter search_submit" href="javascript:void(0)" id="search_button">Search</a>
				</div>
				<input type="hidden" name="mateID" value="{{ base64_encode($mateID) }}" />
 			</form>
 		</div>		
		@if( count($events)>0 )
			@foreach( $events as $event )
				<div class="list-row">
					<div class="list-col">
						<span>{{ @$event->name }}</span>
						<p>
							<i class="fa fa-calendar" aria-hidden="true"></i>
							{{ showDateTime($event->startdatetime, $event->timezone, "D d M h:i A") }}
						</p>
					</div>
					<div class="list-col cmts matchs_btn btn-left">
						<a href="{{ url('sports/event/'.$event->id.$concatStr) }}" class="cmt_btn">View Detail</a>
						<a href="{{ url('make/bet/'.$event->id.$concatStr) }}" class="accpet_btn">Place a Wager</a>
					</div>
 				</div>
			@endforeach
		@else
			<div class="mate" style="text-align: center; width:auto !important; margin:5px; display:block;">Sorry! No record found.</div>
		@endif
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

@section('script')
	<script type="text/javascript">
		
		function goBack() {
			window.history.back();
		}
		
		$(document).ready(function(){
			
			$(document).off('click','#search_button').on('click','#search_button',function(){
				$('#search_form').submit();
			});
		});
	</script>
@endsection
