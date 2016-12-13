@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
			<div class="mid_section">
 				<div class="list_outer outcome_outer no_bg_hover">
					<?php 
						if ( isset($passedMateID) && $passedMateID > 0 ) {
							$concatStr = '?m='.$passedMateID;
						} else {
							$concatStr = '';
						}
					?>
					<h1>
						<div class="challenge_continue">
							<span>Match Stats</span>
						</div>

						<div class="challenge_continue">
							<a class="pull-right btn accpet_btn" href="{{ url('sports/competition/'.@$match->competition->id.$concatStr) }}" style="color:#fff; margin:5px;font-family:ubuntumedium; font-size: 14px;padding: 6px 15px;width: auto;" >Back</a>
						</div>

					</h1>
<!--
 					<h1><span>Match Stats</span></h1>
 					{{ url('banter-board?e='.@$match->id. '&m='. Auth::user()->id ) }}
-->
 					<div class="list-row stats_outer">
 						<div class="list-col event-heading">
 							<span class="match-name">{{ ucwords(@$match->name) }}</span>
 							<p style="font-size:13px;">{{ strtoupper(@$match->competition->name) }}<br>
							{{ ucwords('Harare Sports Club, Sydney') }} </p>
 						</div>
 						
 						<div class="list-col cmts pull-right matchs_btn event-heading">
 							<a href="#" class="accpet_btn" style="visibility:visible; text-transform:uppercase;">Match banter board</a>
 							<p style="margin-top:5px;">
								<i class="fa fa-calendar" aria-hidden="true"></i>
								{{ showDateTime($match->startdatetime, $match->timezone, "D d M h:i A") }}
							</p>

 						</div>
 					</div>
 					<div class="list-row" style="background:#fff; border:none;">
 						<div class="list-col">
 							
 							
		 				<div class="list_outer stats_title" style="min-height:inherit;">
		 					<h1><span>Wager Stats on Mate V Mate</span></h1>
		 					<div class="list-row list-title">
		 						<div style="width:40%;" class="list-col">{{ @$match->team1 }}</div>
		 						<div style="width:20%;" class="list-col">Vs</div>
		 						<div style="width:40%;" class="list-col">{{ @$match->team2 }}</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:40%;" class="list-col">
		 							{{ @$team1['total_wagers'] }}
		 						</div>
		 						<div style="width:20%;" class="list-col">
		 							Total Bets
								</div>
								<div style="width:40%;" class="list-col">
		 							{{ @$team2['total_wagers'] }}
								</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:40%;" class="list-col">
		 							{{ @$team1['wagers_won'] }}
		 						</div>
		 						<div style="width:20%;" class="list-col">
		 							Won
								</div>
								<div style="width:40%;" class="list-col">
		 							{{ @$team2['wagers_won'] }}
								</div>
		 					</div>
		 					
		 					<div class="list-row">
		 						<div style="width:40%;" class="list-col">
		 							{{ @$team1['wagers_lost'] }}
		 						</div>
		 						<div style="width:20%;" class="list-col">
		 							Lost
								</div>
								<div style="width:40%;" class="list-col">
		 							{{ @$team2['wagers_lost'] }}
								</div>
		 					</div>
		 					
<!--
		 					<div class="list-row">
		 						<div style="width:40%;" class="list-col">
		 							4		 						</div>
		 						<div style="width:20%;" class="list-col">
		 							Draw
								</div>
								<div style="width:40%;" class="list-col">
		 							10
								</div>
		 					</div>
-->
		 				</div>
	
 						</div>
 						<div class="list-col cmts matchs_btn all_btn">
							@if( !count(@$favorite) )
								<a href="javascript:void(0)" data-id="{{ @$match->id }}" id="add_fav" class="accpet_btn" style="visibility:visible;display:block;background:#fc9d4b;">Add to Favourites</a>
							@endif
 							<a href="javascript:void(0)" data-id="{{ @$match->id }}" id="view-existing-wager" class="accpet_btn" style="visibility:visible;display:block;background:#485460;">View existing Wagers</a>
							<a href="{{ url('make/bet/'.$match->id) }}" class="accpet_btn" style="visibility:visible;display:block;background:#60ab59;">Challenge a Mate</a>
 						</div>
 					</div>
 					<div id="view-existing-wagers" style="display:none" class=""></div>
 										
										
				</div>
 				
 			</div>	
 	<style>
 	.list-row p{
		text-transform: none;
	}
	.matchs_btn a {
    margin-left: 36px;
}
 	</style>
 	
 	
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
@section('script')
	<script type="text/javascript">	
		
		$(document).ready(function(){
			// add event to favorite list
			$(document).off('click','#add_fav').on('click','#add_fav', function(e){
				e.preventDefault();
				var eventID = $(this).attr('data-id');
				if( eventID ){
					var url = siteurl+'/match-detail/add-to-fav';
					$.ajax({
						type: 'post',
						url : url,
						data : { 'eventID':eventID,'_token':"{{ csrf_token() }}"   },
						success: function( response){
							if(response){
								$('#add_fav').hide();
								generate('success', 'Request completed successfully');
							} else {
								generate('error', 'Some error occured while processing the  request. Please try again in some time.');
							}
						}
						
						});
				} else {
					return false;
				}
			});
			
			
			// view existing wagers
			$(document).off('click','#view-existing-wager').on('click','#view-existing-wager', function(e){
				e.preventDefault();
				var eventID = $(this).attr('data-id');
				if( eventID ) {
				if( $('#view-existing-wagers').hasClass('active') ){
					$('#view-existing-wagers').removeClass('active');
					$('#view-existing-wagers').html('');
					$('#view-existing-wagers').css('display','none');
				}else {
					var url = siteurl+'/match-detail/view-existing-wagers';
					$.ajax({
						type: 'post',
						url : url,
						data : { 'eventID':eventID,'_token':"{{ csrf_token() }}" },
						dataType: 'text',
						success: function( response){
							if( response != 0 ){
								$('#view-existing-wagers').html('');
								$('#view-existing-wagers').addClass('active');
								$('#view-existing-wagers').css('display','block');
								$('#view-existing-wagers').html(response);
								//$('#add_fav').hide();
								//generate('success', 'Request completed successfully');
							} else {
								generate('error', 'Some error occured while processing the  request. Please try again in some time.');
							}
						}
					});
					}
						
				} else {
					return false;
				}
			});
			
			//~ $("#openChallenges").mCustomScrollbar(
					//~ {
						//~ theme:"dark-3",
						//~ scrollButtons:{enable:true},
						//~ scrollInertia:400
					//~ }
			//~ );
		});
 	</script>
@endsection
