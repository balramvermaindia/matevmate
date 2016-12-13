@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="profile_outer">
		@include('sections.profileTabs')

		<div class="list_outer no_bg_hover" style="margin-top:50px; ">

			<div style="margin-top:-42px; margin-left:-1px; margin-right:-1px;">
				@include('sections.preferencesTabs')
			</div>
			
			<div class="list-row">
				<h1><a href="javascript:void(0)" class="btn_green pull-right" id ="show-modal" data-backdrop="static"><i class="fa fa-plus" aria-hidden="true"></i>Add</a></h1>
			 </div>
			 
			 <div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" id="modal-cross">&times;</button>
						<h4 class="modal-title">Add Favorite Match</h4>
					  </div>
					  <form id="match_form">
						  <div class="modal-body">
							<span id="type-search">Type to search: </span>
							<div style="margin-top:5px !important;">
								<input type="text" class="validate[required]" name="fav_matches" data-errormessage-value-missing="This field is required" id="fav_matches" data-prompt-position="topRight:-150"/>
							</div>
							<div style="margin-top:5px !important; display:none" id="empty-message"></div>
						  </div>
						  <div class="modal-footer">
							<input type="submit" class="btn btn-primary" id="add-match" name="name" value="Add"/>
							<button type="button" class="btn btn-default" id="modal-close">Cancel</button>
						  </div>
					  </form>
					</div>
				</div>
			</div>	
			
			<div class="scroll_outer list_outer yes_bg" style="margin-bottom: 0px; border: none; ">
				<div class="scroll">
					@if( count($matches)>0 )
						<div class="list-row list-title">
							<div class="list-col" style="width:10%;">Sport</div>
							<div class="list-col" style="width:37%;">Match</div>
							<div class="list-col" style="width:27%;">Time</div>
							<div class="list-col" style="width:23%;">Action</div>
							<a href="#" class="cross" style="visibility:hidden;"><i class="fa fa-remove" aria-hidden="true"></i></a>
						</div>
					@endif
					@if( count($matches)>0 )
						@foreach($matches as $match)
							<div class="list-row">
								<div class="list-col" style="width:10%;">
									<?php $img_url = getSportsImageBySportsID( @$match->match->betfair_sports_id ); ?>
									<img src='{{ url("$img_url") }}' style="display:inline-block"/>
								</div>
								<div class="list-col" style="width:37%;">
									{{ @$match->match->name }}
								</div>
								<div class="list-col" style="width:27%;">
									{{ showDateTime(@$match->match->startdatetime, @$match->match->timezone, "D d M h:i A") }}
								</div>
								<?php 
								$currentDateTime 	= date('Y-m-d\TH:i:s\Z');
								if( @$match->match->startdatetime >=$currentDateTime && @$match->match->running_status =="pending" ){?>
									<div class="list-col" style="width:23%;">
										<a href="{{ url('make/bet/'.$match->match->id) }}" class="upcoming_matches">Place a Wager </a>
									</div>
								<?php } else {
								?>
								--
								<?php } ?>
								<a href="javascript:void(0)" class="cross" onClick= "deleteFunction('{{ url('remove/favorite/match/'.$match->id) }}','Are you sure you want to remove this match?','Confirmation Required')"><i class="fa fa-remove" aria-hidden="true"></i></a>
							</div>
						@endforeach
					@else
						<div class="mate no_record_found" style="text-align:center; margin-top:14px;">Sorry! No record found.</div>
					@endif
				</div>
			</div>
		</div>
	</div>	
</div>	
<style>
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
	
	 .ui-autocomplete {
		max-height: 210px;
		overflow-y: auto;
		left: 31px;
		right:31px;
		top: 146px;
		width: auto !important;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
	max-width:558px;
}
	
	 
</style>		
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
@section('script')
<script type="text/javascript"> 
	
	
</script>
	 <script>
		
	$(function() {
		
		formId = '#match_form'; 
		$("input").on('click', '', function() {
			var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
			$(inputFieldFormError).fadeOut(150, function() {
				$(this).remove();
			});
		});
		$(formId).validationEngine('attach',{
			autoHidePrompt:true, 
			autoHideDelay: 5000,
			
		});
		
	
		var item;
		$( "#fav_matches" ).autocomplete({
			source		: siteurl + "/suggest/matches",
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
				$("#fav_matches").val(ui.item.label);
				item = ui.item.value;
			}
		});
	
	
		$('#add-match').on('click', function(event, ui){
			event.preventDefault();
			if (item) {
				$('#myModal').modal('hide');
				var data = { 'match': item, '_token': "{{ csrf_token() }}" };
				$.ajax({
					url:  siteurl + "/add-favorite-match",
					type: 'POST',
					data	: data,
					dataType: 'text',
					success: function(response) {
						if (response == '1') {
							$('#fav_matches').val('');
							generate('success', 'Match added successfully');
						} else {
							generate('error', 'An unknown error occured. Please reload the page to continue');
						}
					},
					complete: function() {
						setTimeout(function(){ window.location.href= siteurl+'/profile/preferences/matches' }, 1000);
					}
				});
			} else {
				$('#fav_matches').focus();
			}
		});
		
		
		$('#modal-close').on('click',function(e){
			e.preventDefault();
			$('#fav_matches').val('');
			$('#myModal').modal('hide');
			$("#empty-message").css("display","none");
			$("#empty-message").empty();
		});
		
		$(document).off('click','#show-modal').on('click','#show-modal',function(e){
			e.preventDefault();
			$('#myModal').modal('show');
		});
	
		$('#myModal').on('shown.bs.modal', function() {
		  $('#fav_matches').focus();
		});
		
		$('#modal-cross').on('click',function(e){
			e.preventDefault();
			$('#fav_matches').val('');
			$('#myModal').modal('hide');
			$("#empty-message").css("display","none");
			$("#empty-message").empty();
		});
	
	$(document).off('kepup','#fav_matches').on('keyup','#fav_matches',function(e){
		e.preventDefault();
		if( $('#fav_matches').val() == ''){
			$("#empty-message").css("display","none");
			$("#empty-message").empty();
		}
	});
		
	});
</script>
@endsection
