@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
 			
		<div class="profile_outer">
			@include('sections.matesTabs')

			<div class=" search_outer">
			<div class="list-row search-filter">
				<div class="search">
					<input type="text" placeholder="Search for Name and Email" name="needle" id="needle" value="<?php if(isset($_POST['needle']) && !empty($_POST['needle'])) echo $_POST['needle']; ?>"/>
				</div>
			<div class="search" style="width:40%;">
				<a href="javascript:void(0);" id="more_filter" class="more_filter">
					<i class="fa fa-align-justify" aria-hidden="true"></i>More Filters
				</a>
				<a href="javascript:void(0);" id="search_btn" class="more_filter search_submit">Search</a>

				<div class="filter_popup" style="display:none;" id="filter_popup">
					<select class="text" name="rate_filter" id="rate_filter">
						<option class="filter_opt" value="">Any rating</option>
						<option class="filter_opt" value="5">Atleast 5 star rating</option>
						<option class="filter_opt" value="4">Atleast 4 star rating</option>
						<option class="filter_opt" value="3">Atleast 3 star rating</option>
						<option class="filter_opt" value="2">Atleast 2 star rating</option>
						<option class="filter_opt" value="1">Atleast 1 star rating</option>
					</select>
					<select class="text" style="margin-bottom:0px;" id="bet_filter">
						<option>Any betting</option>
						<option>Atleast 10 bets</option>
						<option>Atleast 20 bets</option>
						<option>Atleast 30 bets</option>
						<option>Atleast 40 bets</option>
					</select>
				</div>
			</div>
			</div>
			 
			 <div class="mates_outer">
				 @if( count(@$mates) )
					@foreach( $mates as $mate )
						<?php $name = @$mate->firstname . ' ' .@$mate->lastname; ?>
						<div class="mate matelen">
							<div class="mate_detail">
								<div class="cmt_pic" style="left:0px;">
									@if ( null != @$mate->photo )
										<a href="{{ url('user-profile/'.$mate->id) }}" target="_blank"><img class="avatar" src="{{ url('assets/users/img/user_profile/'.@$mate->photo) }}" style="width:100%; height:100%;"/></a>
									@else
										<a href="{{ url('user-profile/'.$mate->id) }}" target="_blank"><img class="avatar" src="{{ url('assets/img/cmt.png') }}"></a>
									@endif
								</div>
								<div class="mate_name"><a href="{{ url('user-profile/'.$mate->id) }}" target="_blank"> <?php echo ucwords(@$name); ?></a></div>
								<div class="stars"><img src="{{ url('assets/img/stars.png') }}"/></div>
								<div class="bets"><span>{{ @$mate->total_wagers }}</span>@if(@$mate->total_wagers == '0' || @$mate->total_wagers == '1') Wager @else Wagers @endif</div>
								<div class="bets_divide"><span style="color:#019c6b;">{{ @$mate->wagers_won }} won </span> / <span style="color:#ba4c4c;">{{ @$mate->wagers_lost }} lost</span></div>
							</div>
<!--
							<a href="{{ url('user-profile/'.$mate->id) }}" target="_blank">VIEW PROFILE</a>
-->
							<a href="{{ url('sports?m='. @$mate->id) }}">PLACE A WAGER</a>
					
					
							@if (in_array($mate->id, $request_sent_array))
								<a href="{{ url('/mates/pending-invitations/sent') }}" class="pull-right">VIEW INVITATION</a>
							@elseif (in_array($mate->id, $request_received_array))
								<a href="{{ url('/mates/pending-invitations') }}" class="pull-right">VIEW INVITATIONS</a>
							@elseif ( !in_array($mate->id, $final_array) )
								<a href="{{ url('add/mate/'.$mate->id) }}" class="pull-right">ADD MATE</a>
							@endif
					
						</div>

					@endforeach
				 @else
					<div class="mate" style="text-align: center; width:100% !important;">No mate found.</div>
				 @endif
			 </div>
			
		</div>
		<div class="no_record_found text-center notty-loader" id="loader" style="display:none; margin-bottom: -11px;
		margin-top: -24px;">
			<img src="{{ url('assets/users/img/pre_loader.gif') }}"/>
		</div>
		<div class="challenge_msg no_record_found text-center notty-loader" style="display:none; float:none; margin-bottom: 0 !important; margin-top: -18px !important;" id="no-more-folk">
			<b>That's all.</b>
		</div>
		</div>
		
	</div>
 	<style>
		.list-col img{
			margin-left: 0px;
		}
		.mate_name a:hover{border-bottom:1px solid #000;}
 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
<script>
var page_number = 1;
var loading = false;
$(document).ready(function() {
	$('#needle').focus();
	$('#search_btn').on('click', function(e) {
		e.preventDefault();
		var needle = $('#needle').val();
		$('#search_form').submit();
	});
	
	$('#more_filter').on('click', function() {
		$('.filter_popup').toggle();
	});
	
	$('#rate_filter').on('change', function() {
		$('#search_form').submit();
	});
	
	$('body').click(function(evt){
		console.log(evt.target.id);   
		if(evt.target.id == "rate_filter")
			return;
		if(evt.target.id == "more_filter")
			return;
			
		if(evt.target.id == "bet_filter")
			return;
			
		if(evt.target.id == "filter_popup")
			return;

		if($(evt.target).closest('#rate_filter').length)
			return;   
			          
		if($(evt.target).closest('#bet_filter').length)
			return;
			             
		if($(evt.target).closest('#filter_popup').length)
			return;   
			          
		if($(evt.target).closest('#more_popup').length)
			return;             
			
		
		$('.filter_popup').css('display', 'none');
		
		///$("#firstName").focus();
	});
	
	
	// load more mates on scroll
	
	$(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
			var mateslen = $('.matelen').length;
			if( mateslen ) {
				if(loading == false){
					page_number++; //page number increment
					loading = true;  //set loading flag on
					var needle = $('#needle').val();
					var url = siteurl+'/mates/search-mates';
					var data = { "page_number" : page_number, "needle" : needle, "pagination":"true"  } 
					//loadMore( url );
					$.ajax({
						url	: url,
						type: 'get',
						data: data,
						dataType: 'text',
						beforeSend: function() {
										$('#loader').show();
									},
						complete: function(){
									$('#loader').hide();
									},
						success: function(response){
							loading = false;
							if( response != "false"){
								$('.mates_outer').append(response);
							}else{
								loading = true;
								$('#no-more-folk').css('display','block');
							}
							
						}
					});
				}	
			} else {
				return false;
			}
		}
	});	
	
	$(document).off('click','#search_btn').on('click','#search_btn', function(e){
		e.preventDefault();
		var needle = $('#needle').val();
		var rate_filter = $('#rate_filter').val();
		var bet_filter = $('#bet_filter').val();
		var url = siteurl+'/mates/search-mates';
		var data = { "filters": "true", "needle":needle, "rate_filter" : rate_filter, "bet_filter": bet_filter, "_token" :"{{ csrf_token() }}" };
		$.ajax({
			type: 'post',
			url: url,
			data: data,
			dataType:'text',
			success: function( response ) {
				if( response ) {
					$('.mates_outer').html('');
					$('.mates_outer').html(response);
					$('#no-more-folk').css('display','none');
					page_number = 1;
					loading = false;
					
				} else {
					return false;
				}
			}			
		});
		
	});
	
	$(document).off('keypress','#needle').on('keypress','#needle', function(e) {
		var key = e.which;
		if ( key == 13 ) {
			$('#search_btn').trigger('click');
		}
	});
});
</script>

@endsection

