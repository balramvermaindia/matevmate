@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="profile_outer">
		@include('sections.profileTabs')

		<div class="list_outer no_bg_hover" style="margin-top:50px;">
			<div style="margin-top:-42px; margin-left:-1px; margin-right:-1px;">
				@include('sections.preferencesTabs')
			</div>
			
			<div class="list-row">
				<h1><a href="javascript:void(0)" class="btn_green pull-right" id="show-modal" data-toggle="modal" data-backdrop="static"><i class="fa fa-plus" aria-hidden="true"></i>Add</a></h1>
			 </div>
			
			<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" id="modal-cross">&times;</button>
						<h4 class="modal-title">Add Favorite Team</h4>
					  </div>
					  <form id="team_form">
						  <div class="modal-body">
							<span>Type to search:</span>
							 <div style="margin-top:5px !important;">
								 <input type="text" name="fav_teams" id="fav_teams" class="validate[required]" data-errormessage-value-missing="This field is required" data-prompt-position="topRight:-150" />
							</div>
							<div style="margin-top:5px !important; display:none" id="empty-message"></div>
						  </div>
						  <div class="modal-footer">
							<input type="submit" class="btn btn-primary" id="add-team" name="add" value="Add"/>
							<button type="button" class="btn btn-default" id = "modal-close">Cancel</button>
						  </div>
					 </form>
					</div>
				</div>
			</div>		
			
			
			<div class="scroll_outer list_outer yes_bg" style="margin-bottom: 0px; border: none; ">
				<div class="scroll">
					@if( count($teams)>0 )
						<div class="list-row list-title">
							<div class="list-col" style="width:25%;">Team</div>
							<div class="list-col" style="width:23%;">Matches</div>
							<div class="list-col" style="width:24%;">Bets placed</div>
							<div class="list-col" style="width:25%;">Upcoming Matches</div>
							<a href="#" class="cross" style="visibility:hidden;"><i class="fa fa-remove" aria-hidden="true"></i></a>
						</div>
					@endif
					@if( count($teams)>0 )
						@foreach( $teams as $team )
							<div class="list-row">
								<div class="list-col" style="width:25%;">
									{{ @$team->name }}
								</div>
								<div class="list-col" style="width:23%;">
									{{ @$team->matches_total }} ({{ @$team->matches_won }} won/ {{ @$team->matches_lost }} Lost)
								</div>
								<div class="list-col" style="width:24%;">
									{{ @$team->challenges_total }} ({{ @$team->challenges_won }} won/ {{ @$team->challenges_lost }} Lost)
								</div>
								<div class="list-col" style="width:25%;">
									<a href="#" class="upcoming_matches">Upcoming Matches</a>
								</div>
								<a href="javascript:void(0)" class="cross" onClick= "deleteFunction('{{ url('remove/favorite/team/'.$team->id) }}','Are you sure you want to remove this team?','Confirmation Required')"><i class="fa fa-remove" aria-hidden="true"></i></a>
							</div>
						@endforeach
					@else
						<div class="mate no_record_found" style="text-align: center; margin-top:14px;">Sorry! No record found.</div>
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
	#fav_teams{
		border: 1px solid #ddd;
		height: 32px;
		width: 100%;
	}
	#myModal .modal-body{
		text-align:left;
	}
	.modal-body > span {
		font-size: 14px !important;
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
	 <script>
		
	$(function() {
		
		formId = '#team_form'; 
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
		$( "#fav_teams" ).autocomplete({
			source		: siteurl + "/suggest/teams",
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
				item = ui.item.value;
			}
	});
	
	$('#add-team').on('click', function(e, ui){
			e.preventDefault();
			if(item){
				$('#myModal').modal('hide');
				var data = { 'team': item, '_token': "{{ csrf_token() }}" };
				$.ajax({
					url:  siteurl + "/add-favorite-team",
					type: 'POST',
					data	: data,
					dataType: 'text',
					success: function(response) {
								if (response == '1') {
								$('#fav_teams').val('');
								generate('success', 'Team added successfully');
								} else {
								generate('error', 'An unknown error occured. Please reload the page to continue');
								}
					},
					complete: function() {
						setTimeout(function(){ window.location.href= siteurl+'/profile/preferences/teams' }, 1000);
					}
				});
			}else{
				$('#fav_teams').focus();
			}
		});
	$('#modal-close').on('click',function(e){
		e.preventDefault();
		$('#fav_teams').val('');
		$('#myModal').modal('hide');
		$("#empty-message").css("display","none");
		$("#empty-message").empty();
	});
	
	$('#modal-cross').on('click',function(e){
		e.preventDefault();
		$('#fav_teams').val('');
		$('#myModal').modal('hide');
		$("#empty-message").css("display","none");
		$("#empty-message").empty();
	});
	
	$(document).off('kepup','#fav_teams').on('keyup','#fav_teams',function(e){
		e.preventDefault();
		if( $('#fav_teams').val() == ''){
			$("#empty-message").css("display","none");
			$("#empty-message").empty();
		}
	}); 
	
	$(document).off('click','#show-modal').on('click','#show-modal',function(e){
		e.preventDefault();
		$('#myModal').modal('show');
	});
	
	$('#myModal').on('shown.bs.modal', function() {
	  $('#fav_teams').focus();
	});
	
});
</script>
@endsection
