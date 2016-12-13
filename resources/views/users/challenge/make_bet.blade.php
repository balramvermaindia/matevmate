@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
	<div class="list_outer outcome_outer no_bg_hover continue_challange_outer">
		<h1 class="continue_challange">
			<div class="challenge_continue">
				<span>{{ $event->name }}</span> 
			</div>
			<div class="challenge_continue">
				<a class="pull-right btn accpet_btn" style="color:#fff;margin:5px;font-family: 'open_sanssemibold'; font-size: 14px;padding: 6px 15px;width: auto;" href="javascript:void(0);">Continue</a>
			</div>
			<input type="hidden" name="passedMateID" value="{{ $passedMateID }}" id="passedMate"/>
		</h1>
		 <div style="padding:15px 0px;" id="steps_main_container">
			<div class="steps">
				<span class="sel_step"><i class="fa fa-group"></i>Choose Team</span>
				<span class=""><i class="fa fa-user"></i>Select Mate</span>
				<span><i class="fa fa-beer"></i>Pick Wager</span>
				<span><i class="fa fa-file-text"></i>Place Wager</span>
			</div>
			
			<div class="sel-match">
				<p>{{ $event->name }}</p>
				<span><i class="fa fa-calendar"></i> &nbsp; {{ showDateTime($event->startdatetime, $event->timezone, "d M, Y | h:i A") }} </span>
				
				<div class="choose_team">
					<div>	
						<a href="javascript:void(0);" class="" id="team1"><i class="fa fa-check"></i>{{ $event->team1 }}</a>
					</div>
					<div style="width:10%;border:none;" class="middle-div"></div>
					<div>
						<a href="javascript:void(0);" class="" id="team2"><i class="fa fa-check"></i>{{ $event->team2 }}</a>
					</div>
				</div>
				<div class="cmts continue" style="margin:20px;"> <a class="accpet_btn" id="contine_and_accept" href="javascript:void(0);">Continue</a></div>
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

@section('script')
<script>
	$(document).ready(function() {
		$(document).on('click', '#team1,#team2', function(e) {
			e.preventDefault();
			var cls = $(this).attr('class');
			
			if ( cls == '' ) {
				$('#team1').removeClass('select');
				$('#team2').removeClass('select');
				$(this).addClass('select');
			}
		});
		
		$(document).off('click', '.accpet_btn').on('click', '.accpet_btn', function(e) {
			e.preventDefault();
			var selectedTeam = $('div.sel-match').children('div.choose_team').children('div').children('a.select').attr('id');
			var passedMateID = $('#passedMate').val();
			if ( selectedTeam ) {
				
				var data 	= {"selectedTeam" : selectedTeam, "event":"{{ $event->id }}", "_token":"{{ csrf_token() }}","passedMateID":passedMateID,"hiddenField":"hidden" };
				$.ajax({
					url:  siteurl + "/select/mate",
					type: 'POST',
					data	: data,
					dataType: 'text',
					success: function(response) {

						if (response && response != 'false') {
							$('#steps_main_container').html('');
							$('#steps_main_container').html(response);
						} else {
							generate('error', 'An unknown error occured. Please reload the page to continue');
						}
					},
					error: function(){
						
					}
				});
			} else {
				generate('error', 'Please select a team to continue');
			}
			
		});
		
		
	});
</script>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
