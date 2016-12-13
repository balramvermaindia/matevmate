@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="list_outer outcome_outer" style="min-height:inherit;">
		<?php 
				if ( isset($passedMateID) && $passedMateID > 0 ) {
					$concatStr = '?m='.$passedMateID;
				} else {
					$concatStr = '';
				}
			?>
		<h1>
			<div class="challenge_continue">
				<span>{{ @$sport_name }}</span>
			</div>
			<div class="challenge_continue">
				<a class="pull-right btn accpet_btn" href="{{ url('sports'.$concatStr) }}"  style="color:#fff; margin:5px; font-family:ubuntumedium; font-size: 14px;padding: 6px 15px;width: auto;">Back</a>
			</div>
		</h1>		
		@if( count($competitions)>0 )
			@foreach( $competitions as $competition)
				<a href="{{ url('sports/competition/'.$competition->id.$concatStr) }}"><div class="list-row">
					<div class="list-col">
						<span>{{ $competition->name }}</span>
					</div>
				</div></a>
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
