@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="profile_outer">
		<div class="profile_title">
			<span>Sports</span>
		</div>
		<div class="list_outer">
			<h1><span>Events</span> <a href="#" class="btn_green pull-right" style="display:none"><i class="fa fa-plus" aria-hidden="true"></i></a></h1>
			<div class="scroll_outer">
				<div class="scroll">
					<div class="list-row list-title">
						
						<div class="list-col" style="width:40%;">Name</div>
						<div class="list-col" style="width:40%;">Open Time</div>
					</div>
					@if( count($events)>0 )
						@foreach( $events as $event)
							<div class="list-row">
								<div class="list-col" style="width:50%;">
									<a style="color:black; width:60%;" href="#">{{ $event->name }}</a>
								</div>
								<div class="list-col" style="width:50%;">
									<a style="color:black; width:40%;" href="#">{{ date("D d M hA", strtotime($event->startdatetime))}}</a>
								</div>
							</div>
						@endforeach
					@else
						<div class="mate" style="text-align: center; width:100% !important;">Sorry! No record found.</div>
					@endif
				</div>
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
