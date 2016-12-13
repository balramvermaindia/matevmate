@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 	<div class="profile_outer">
		@include('sections.profileTabs')
		<div class="list_outer no_bg_hover" style="margin-top:50px;">
<!--
			<h1><span>Favorite Teams</span> <a href="#" class="btn_green pull-right"><i class="fa fa-plus" aria-hidden="true"></i>Add</a></h1>
-->
			<div style="margin-top:-42px; margin-left:-1px; margin-right:-1px;">
				@include('sections.preferencesTabs')
			</div>
			
			 <div class="list-row">
				<h1><a href="javascript:void(0)" class="btn_green pull-right" id="show-modal" data-backdrop="static"><i class="fa fa-plus" aria-hidden="true"></i>Add</a></h1>
			 </div>
			 
			 <div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" id="modal-cross">&times;</button>
						<h4 class="modal-title">Add Favorite Sport</h4>
					  </div>
					  <form id="sport_form">
						  <div class="modal-body">
							<span>Type to search:</span> 
							<div style="margin-top:5px !important;">
								<input type="text" name="fav_sport" id="fav_sports" class="validate[required]" data-errormessage-value-missing="This field is required" data-prompt-position="topRight:-150"/>
							</div>
							<div style="margin-top:5px !important; display:none" id="empty-message"></div>
						  </div>
						  <div class="modal-footer">
							<input type="submit" class="btn btn-primary" id="add-sport" name="add" value="Add">
							<button type="button" class="btn btn-default" id="modal-close">Cancel</button>
						  </div>
					</form>
					</div>
				</div>
			</div>	
			
			<div class="scroll_outer list_outer yes_bg" style="margin-bottom: 0px; border: none; ">
				<div class="scroll">
					@if( count($sports)>0 )
						<div class="list-row list-title">
							<div class="list-col" style="width:25%;">Sport</div>
							<div class="list-col" style="width:47%;">Name</div>
							<div class="list-col" style="width:25%;">Upcoming Matches</div>
							<a href="#" class="cross" style="visibility:hidden;"><i class="fa fa-remove" aria-hidden="true"></i></a>
						</div>
					@endif
					@if( count($sports)>0 )
						@foreach( $sports as $sport )
							<div class="list-row">
								<div class="list-col" style="width:25%;">
									<?php $img_url = getSportsImageBySportsID( @$sport->sport->id ); ?>
									<img src='{{ url("$img_url") }}' style="display:inline-block"/>
								</div>
								<div class="list-col" style="width:47%;">
									{{ @$sport->sport->event_name }}
								</div>
								<div class="list-col" style="width:25%;">
									<a href="{{ url('sports/'.$sport->sport->sports_sysname) }}" class="upcoming_matches">Upcoming Matches</a>
								</div>
								<a href="javascript:void(0)" class="cross" onClick= "deleteFunction('{{ url('remove/favorite/sport/'.$sport->id) }}','Are you sure you want to remove this sport?','Confirmation Required')"><i class="fa fa-remove" aria-hidden="true"></i></a>
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
	#fav_sports{
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
		
		formId = '#sport_form'; 
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
		$( "#fav_sports" ).autocomplete({
			source	: siteurl + "/suggest/sports",
			response: function(event, ui) {
            if (ui.content.length === 0) {
					$("#empty-message").css("display","block");
					$("#empty-message").text("No results found");
				} else {
					$("#empty-message").css("display","none");
					$("#empty-message").empty();
				}
			},
			select  : function( event, ui ) {
				item = ui.item.value;
			 }
	    });
	
	$('#add-sport').on('click', function(event, ui){
		event.preventDefault();
		if(item){
			$('#myModal').modal('hide');
			var data	= { 'sport': item, '_token': "{{ csrf_token() }}" };
			$.ajax({
				url		:  siteurl + "/add-favorite-sport",
				type	: 'POST',
				data	: data,
				dataType: 'text',
				success	: function(response) {
					if (response == '1') {
						$('#fav_sports').val('');
						generate('success', 'Sport added successfully');
					}else {
						generate('error', 'An unknown error occured. Please reload the page to continue');
					}
				},
				complete: function() {
					setTimeout(function(){ window.location.href= siteurl+'/profile/preferences/sports' }, 1000);
				}
			});
		}else{
			$('#fav_sports').focus();
		}
	});
	$('#modal-close').on('click',function(e){
		e.preventDefault();
		$('#fav_sports').val('');
		$('#myModal').modal('hide');
		$("#empty-message").css("display","none");
		$("#empty-message").empty();
	});
	$('#modal-cross').on('click',function(e){
		e.preventDefault();
		$('#fav_sports').val('');
		$('#myModal').modal('hide');
		$("#empty-message").css("display","none");
		$("#empty-message").empty();
	});
	
	$(document).off('kepup','#fav_sports').on('keyup','#fav_sports',function(e){
		e.preventDefault();
		if( $('#fav_sports').val() == ''){
			$("#empty-message").css("display","none");
			$("#empty-message").empty();
		}
	}); 
	
	$(document).off('click','#show-modal').on('click','#show-modal',function(e){
		e.preventDefault();
		$('#myModal').modal('show');
	});
	
	$('#myModal').on('shown.bs.modal', function() {
	  $('#fav_sports').focus();
	});
});
</script>
@endsection
