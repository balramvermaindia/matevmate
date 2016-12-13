@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="list_outer outcome_outer">
 		<h1><span>All Sports</span></h1>
		<div class="sport_outer">
			@if( count($sports)>0 ) 
				<?php 
					if ( isset($passedMateID) && $passedMateID > 0 ) {
						$concatStr = '?m='.$passedMateID;
					} else {
						$concatStr = '';
					}
				?>
				@foreach( $sports as $sport )
					<a href="{{ url('sports/'.$sport->sports_sysname.$concatStr) }}" class="sport {{ @$sport->sports_class }}">
						<div class="sport_text">{{ @$sport->event_name }}
					</div></a>
				@endforeach	
			@else
				<div class="mate" style="text-align: center; width:100% !important;">Sorry! No record found.</div>
			@endif	
		</div>
 	</div>	
</div>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
