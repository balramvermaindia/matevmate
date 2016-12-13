@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')

	<div class="mid_section">
		<div class="list_outer open_betting no_bg_hover outcome_outer">
			<h1><span>Challenge Details</span> <a class="btn accpet_btn pull-right" href="javascript:void(0)" onclick="goBack()" style="color:#fff;margin:5px;font-family: 'open_sanssemibold'; font-size: 14px;padding: 6px 15px;width: auto;">Back</a></h1>
			<div id="steps_main_container" style="padding:15px 0px;"><div class="challenge_info">
				<div>
					<div class="challenge_row list-row">
						<div class="list-col">
						<label>Match:</label>
						</div>
						
						<?php $img_url = getSportsImageBySportsID( @$challenge->event->betfair_sports_id ); ?>
						<div class="list-col text-right">
						<span style="display:block;"><img src="{{ url($img_url) }}"/>{{ @$challenge->event->name }}</span>
						<p>
							<i aria-hidden="true" class="fa fa-calendar"></i>
							{{ showDateTime(@$challenge->event->startdatetime, @$challenge->event->timezone, "D d M h:i A") }}
						</p>
						</div>
					</div>
					<div class="challenge_row list-row">
						<div class="list-col">
						<label>My wager is on:</label>
						</div>
						<div class="list-col text-right">
							@if(Auth::user()->id == @$challenge->user_id )
								@if( @$challenge->team_id == @$challenge->event->team1_id)
									<span>{{ @$challenge->event->team1 }} to Win</span>
								@else
									<span>{{ @$challenge->event->team2 }} to Win</span>
								@endif
							@else
								@if( @$challenge->team_id == @$challenge->event->team1_id)
									<span>{{ @$challenge->event->team2 }} to Win</span>
								@else
									<span>{{ @$challenge->event->team1 }} to Win</span>
								@endif
							@endif
						</div>
					</div>
					@if(Auth::user()->id == @$challenge->user_id)
						<div class="challenge_row list-row">
							<div class="list-col">
							<label>Wager made with:</label>
							</div>
							<div class="list-col text-right">
							<span>{{ ucwords(@$challenge->mate->firstname .' '.@$challenge->mate->lastname) }}</span>
							</div>
						</div>
					@else
						<div class="challenge_row list-row">
							<div class="list-col">
								<label>Wager made with:</label>
							</div>
							<div class="list-col text-right">
								<span>{{ ucwords(@$challenge->user->firstname .' '.@$challenge->user->lastname) }}</span>
							</div>
						</div>
					@endif
					<div class="challenge_row list-row">
						<div class="list-col">
							<label>Wager at stake:</label>
						</div>
						<div class="list-col text-right">
							<span>{{ ucwords(@$challenge->wager_amount) }}</span>
						</div>
					</div>
					<div class="challenge_row list-row">
						<div class="list-col">
							<label>Challenge status:</label>
						</div>
						<?php 
							$status 	= '';
							if(@$challenge->challenge_status == 'awaiting'){
								$status = 'Awaiting Acceptance';
							}
							if(@$challenge->challenge_status == 'won' || $challenge->challenge_status == 'lost'){
								if(Auth::user()->id == $challenge->winner_user_id){
									$status = 'won';
								}else{
									$status = 'lost';
								}
							}
							if(@$challenge->challenge_status == 'pending'){
								$status = 'Pending';
							}
							if(@$challenge->challenge_status == 'active'){
								$status = 'Active';
							}
							if(@$challenge->challenge_status == 'rejected'){
								$status = 'Rejected';
							}
							if(@$challenge->challenge_status == 'cancelled'){
								$status = 'Cancelled';
							}
						?>
						<div class="list-col text-right">
							<span>{{ ucwords(@$status) }}</span>
						</div>
					</div>
					@if( @$challenge->challenge_status == "lost" || @$challenge->challenge_status == "won")
						<div class="challenge_row list-row">
							<div class="list-col">
								<label>Honour status:</label>
							</div>
							<div class="list-col text-right">
								<span>{{ ucwords(@$challenge->honour_status) }}</span>
							</div>
						</div>
					@endif
				</div>
				@if (@$challenge->accepted_status == "accepted") 
					<div class="cmts continue text-right" style="margin:20px;">
						<a class="btn btn-success btn-sm" href="{{ url('banter-board?e=' . @$challenge->betfair_event_id . '&m='.  @$challenge->mate_id) }}">MATCH BANTER BOARD</a>
					</div>
				@endif
				@if( Auth::user()->id == @$challenge->user_id && @$challenge->challenge_status == "awaiting" )
					<div class="cmts continue text-right" style="margin:20px;">
						<a class="accpet_btn" href="{{ url('make/bet/' . $challenge->betfair_event_id .'?edit=true&c='.$challenge->id) }}">Edit</a>
						<a class="cmt_btn" href="javascript:void(0)" onClick= "deleteFunction('{{ url('/cancel-challenge/'.$challenge->id) }}','Are you sure you want to cancel this challenge?','Confirmation Required')" style="margin-right:10px;">Cancel</a>
					</div>
				@endif
				@if( Auth::user()->id == @$challenge->mate_id && @$challenge->challenge_status == "awaiting")
					<div class="cmts continue text-right" style="margin:20px;">
						<a class="accpet_btn" href="{{ url('/honor-accept/'.$challenge->id) }}" id="">Accept</a>
						<a class="cmt_btn" href="{{ url('/honor-cancel/'.$challenge->id) }}" style="margin-right:10px;">Decline</a>
					</div>
				@endif
				@if( @$challenge->challenge_status == "lost" || @$challenge->challenge_status == "won")  
					@if( @$challenge->honour_status == "pending" && @$challenge->winner_user_id != Auth::user()->id)
					<span style="font-size:15px; margin-left:24px; display:block;margin-top:10px;">Sorry! you lost this challenge, Please honour the wager.</span>
					<div class="cmts continue text-left" style="margin:20px;">
						<a class="accpet_btn honor_btn" href="{{ url('/honor-wagers/'.$challenge->id) }}">Honour Now</a>
						<a class="cmt_btn honor_btn" href="javascript:void(0)" onClick= "deleteFunction('{{ url('cancel-honour-wager/'.$challenge->id) }}','You have selected to cancel the honour of this wager. This will decrease your points and we will notify your mate that you have cancelled the honour. Are you sure you want to cancel the honour?','Cancel Honour')"  style="margin-right:10px;">Cancel Honour</a>
					</div>
					@endif
				@endif
				@if( Auth::user()->id == @$challenge->user_id && @$challenge->challenge_status == "rejected" && @$challenge->accepted_status == "rejected")
					<span style="font-size:15px; margin-left:24px; display:block;margin-top:10px;">Your mate has declined your challenge request, Please select the action</span>
					<div class="cmts continue text-left" style="margin:20px;">
						<a class="accpet_btn" href="{{ url('/post-challenge-to-banter/'.$challenge->id) }}">Post to Public Banter</a>
						<a class="cmt_btn" href="javascript:void(0)" style="margin-right:10px;" id="challenge-other-mate" data-user-id="{{ Auth::user()->id }}" data-mate-id="{{ @$challenge->mate_id }}"/>Challenge Other Mate</a>
					</div>
				@endif
				
<!--
				<div id="myModal" class="modal fade" role="dialog">
					<div class="modal-dialog">	
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" id="modal-cross">&times;</button>
							<h4 class="modal-title">Select Mate</h4>
						  </div>
						  <form id="match_form">
							  <div class="modal-body">
								<div style="margin-top:5px !important;" id="modal-content">
								</div>
								<div style="margin-top:5px !important; display:none" id="empty-message"></div>
							  </div>
							  <div class="modal-footer">
								<input type="submit" class="btn btn-primary"  id ="continue" name="name" value="Continue"/>
								<button type="button" class="btn btn-default" id="modal-close">Cancel</button>
							  </div>
						  </form>
						</div>
					</div>
				</div>
-->
				<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" id="modal-cross">&times;</button>
						<h4 class="modal-title">Select Mate</h4>
					  </div>
					  <form id="match_form">
						  <div class="modal-body">
							<span id="type-search">Type to search: </span>
							<div style="margin-top:5px !important;">
								<input type="text" class="validate[required]" name="mates" data-errormessage-value-missing="This field is required" id="mates" data-prompt-position="topRight:-150"/>
							</div>
							<div style="margin-top:5px !important; display:none" id="empty-message"></div>
						  </div>
						  <div class="modal-footer">
							<input type="submit" class="btn btn-primary" id="continue-btn" name="name" value="Continue"/>
							<button type="button" class="btn btn-default" id="modal-close">Cancel</button>
						  </div>
					  </form>
					</div>
				</div>
			</div>	


			</div>
		</div>
	</div>
	</div>
	<!-- Modal-->

	 	
	<style>
	.accpet_btn {
		float: none;
	}
	.ui-widget-content{
		z-index:11111;
	}
	#fav_matches{
		border: 1px solid #ddd;
		height: 32px;
		width: 100%;
	}
	#myModal .modal-body{
		text-align:left;
	}
	#type-search{
		font-size:14px !important;
	}
	 .ui-autocomplete {
		max-height: 210px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
	}
	#mates {
    border: 1px solid #ddd;
    height: 32px;
    width: 100%;
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
		
		
		var item='';
		var data_id='';
		$(document).ready(function(){
			$(document).off('click','#challenge-other-mate').on('click','#challenge-other-mate',function(e){
				e.preventDefault();
				$('#myModal').modal('show');
			});
			
			$('#myModal').on('shown.bs.modal', function() {
				$('#mates').focus();
			});
			
			
			$( "#mates" ).autocomplete({
				source		: siteurl + "/search-other-mates?mateID=" + $('#challenge-other-mate').attr('data-mate-id'),
				response: function(event, ui) {
				if (ui.content.length === 0) {
						$("#empty-message").css("display","block");
						$("#empty-message").text("No results found");
					} else {
						$("#empty-message").css("display","none");
						$("#empty-message").empty();
					}
				},
				select: function( event, ui ) {
					event.preventDefault();
					$("#mates").val(ui.item.label);
					item = ui.item.label;
					data_id = ui.item.value;
				}
			});
			
			$('#modal-cross').on('click',function(e){
				e.preventDefault();
				$('#mates').val('');
				$('#myModal').modal('hide');
				$("#empty-message").css("display","none");
				$("#empty-message").empty();
			});
			
			$('#modal-close').on('click',function(e){
				e.preventDefault();
				$('#mates').val('');
				$('#myModal').modal('hide');
				$("#empty-message").css("display","none");
				$("#empty-message").empty();
			});
			
			$(document).off('kepup','#mates').on('keyup','#mates',function(e){
				e.preventDefault();
				data_id = '';
				if( $('#mates').val() == ''){
					$("#empty-message").css("display","none");
					$("#empty-message").empty();
				}
			});
			
			$('#continue-btn').on('click', function(event, ui){
			event.preventDefault();
			var challengeID = '<?php echo $challenge->id; ?>';
			if (data_id) {
				$('#myModal').modal('hide');
				var data = {"_token" : "{{ csrf_token() }}", "mateID" : data_id, 'challengeID' : challengeID};
				$.ajax({
					url:  siteurl + "/select-other-mate",
					type: 'POST',
					data	: data,
					dataType: 'text',
					success: function(response) {
						if (response == '1') {
							$('#mates').val('');
							generate('success', 'Challenge Placed successfully');
						} else {
							generate('error', 'An unknown error occured. Please reload the page to continue');
						}
					},
					complete: function() {
						setTimeout(function(){ window.location.href= siteurl+'/challenge-detail/'+challengeID }, 1000);
					}
				});
			} else {
				$('#mates').focus();
			}
		});
		
		
		});
	</script>
@endsection




