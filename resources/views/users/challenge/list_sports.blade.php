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
			<h1><span>Sports</span> <a href="#" class="btn_green pull-right" style="display:none"><i class="fa fa-plus" aria-hidden="true"></i></a></h1>
			<div class="scroll_outer">
				<div class="scroll">
					<div class="list-row list-title">
						
						<div class="list-col" style="width:40%;">Name</div>
					</div>
					@if( count($sports)>0 )
						@foreach( $sports as $sport)
							<div class="list-row">
								<div class="list-col" style="width:40%;">
									<a style="color:black; width:25%;" href="{{ url('sport/'.$sport->id) }}">{{ $sport->event_name }}</a>
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
