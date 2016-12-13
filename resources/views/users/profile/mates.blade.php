@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
 		<div class="profile_outer">
 			@include('sections.matesTabs')
 			<div class="" style="">
				<div class="scroll_outer" style="margin:17px;">
				<div class="scroll vertical-align" id="load-more-mates">
					<?php
						if ( count($userMates) ) {
					?>
						<div class="list-row list-title">
							<div class="list-col" style="width:40%;">Mate</div>
							<div class="list-col" style="width:15%;">Wagers</div>
							<div class="list-col" style="width:13%;">Won</div>
							<div class="list-col" style="width:13%;">Lost</div>
							<div class="list-col" style="width:15%;">
							<a href="javascript:void(0)"   class="btn_red" style="width:60px; float:none;visibility:hidden;">Remove</a>

							</div>
<!--
							<a href="#" class="cross" style="visibility:hidden;"><i class="fa fa-remove" aria-hidden="true"></i></a>
-->
						</div>
					<?php
					}
					?>
				<?php
					if ( count($userMates) ) {
						foreach ( $userMates as $userMate ) {
							$name = @$userMate->mateProfile->firstname . ' ' .$userMate->mateProfile->lastname;
				?>
					<div class="list-row">
						<div class="list-col user_rating" style="width:40%;">
							<div class="small_profile">
								
								@if( $userMate->mateProfile->photo == "" )
								<img src="{{ url('assets/img/cmt.png') }}">
								@else
								<a href="{{ url('user-profile/'.$userMate->mateProfile->id) }}" target="_blank" style="text-decoration:none;"><img src="{{ url('assets/users/img/user_profile/'.@$userMate->mateProfile->photo) }}" style="width:58px;"></a>
								@endif
							</div>
							<a href="{{ url('user-profile/'.$userMate->mateProfile->id) }}" id="font-size" target="_blank" style="text-decoration:none; color:black;">{{ ucwords(@$name) }}</a>
							<p><img src="{{ url('assets/users/img/star_rating1.png') }}" style="height:12px; vertical-align:centre"/></p>
						</div>

						<div class="list-col" style="width:15%;">
							{{ @$userMate->total_wagers }}
						</div>
						<div class="list-col" style="width:13%;">
							{{ @$userMate->wagers_won }}
						</div>
						<div class="list-col" style="width:13%;">
							{{ @$userMate->wagers_lost }}
						</div>
						<div class="list-col" style="width:15%;">
							<a href="javascript:void(0)" onClick= "deleteFunction('{{ url('remove/mate/'.$userMate->mateProfile->id) }}','Are you sure you want to remove this mate?','Confirmation Required')"  class="btn_red" style="width:60px; float:none;">Remove</a>

						</div>
						
<!--
						<a href="javascript:void(0)" onClick= "deleteFunction('{{ url('remove/mate/'.$userMate->mateProfile->id) }}','Are you sure you want to remove this mate?','Confirmation Required')" class="cross"><i class="fa fa-remove" title="Remove Mate" aria-hidden="true"></i></a>
-->
<!--
						<input type="hidden" name="offset" value="{{ $userMate->id }}" class="message_box"/>
-->
					</div>
				<?php
					}
				} else {
				?>
					<div class="mate no_record_found" style="text-align:center; margin-top:14px;">Sorry! You don't have any mate.</div>
				<?php
				}
				?>
				</div>
				
 		</div>
			</div>

 		</div>
 	</div>
 	<style>
		.list-col img{
			margin-left: 0px;
		}
		#font-size{
			font-size:12px !important;
		}
		#font-size {
			background: none !important
		}
		.total_cmts_row {
			border-bottom: none !important;
		}
		.vertical-align .list-col{
			vertical-align: middle;
		}
 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
<!--
	<script>
	$(document).ready(function() {
		$(window).scroll(function() {
			if($(window).scrollTop() + $(window).height() == $(document).height())
			{
				var last_mate_id = $(".message_box:last").val();
				autoload(last_mate_id);
			}
		}); 
	});
	function autoload(last_mate_id)
	{
		$.ajax({
			type: "POST",
			url: "{{ URL::to('profile/load-more-mates') }}",
			data: { last_mate_id: last_mate_id, _token: '<?php // echo csrf_token(); ?>' },
			success: function(data){
				$('#load-more-mates').append(data);
				//console.log(data);
			}
		});
	}
  </script>
-->
@endsection
