@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
		<div class="profile_outer">
			@include('sections.wagerTabs')
				<div class="list-row search-filter" style="padding: 10px 0px">
					<form method="GET" action="{{ url('wagers') }}" id="filter-form">
						<div class="search pull-right text-right">
							<select id="status-filter" name="filter">
								<option value="all" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'all') echo 'selected=selected'; ?>>All</option>
								<option value="awaiting" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'awaiting') echo 'selected=selected'; ?>>Awaiting Acceptance</option>
								<option value="pending" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'pending') echo 'selected=selected'; ?>>Pending</option>
								<option value="active" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'active') echo 'selected=selected'; ?>>Active</option>
								<option value="won" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'won') echo 'selected=selected'; ?>>Won</option>
								<option value="lost" <?php if(isset($_GET['filter']) && $_GET['filter'] == 'lost') echo 'selected=selected'; ?>>Lost</option>
							</select>
						</div>
					</form>
				 </div>
			<div class="my-wagers">
				<div class="@if( count($summary)>0 ) list_outer  @endif">
					<div class="scroll_outer">
						<div class="scroll">
							@if( count($summary)>0 )
								<a class="list-row list-title" style="cursor:default !important; text-decoration:none; color:black;">
									<div class="list-col" style="width:25%;">Mate Name</div>
									<div class="list-col padding_left_match" style="width:35%;">Match</div>
									<div class="list-col" style="width:25%;">Wager</div>
									<div class="list-col" style="width:15%;">Status</div>
								</a>
								<div class="load-more-wagers">
									@foreach( $summary as $sum )
										<a class="list-row wagerlen" href="{{ url('challenge-detail/'.$sum->id) }}" style="color:black">
											@if(Auth::user()->id == $sum->user_id)
												<div class="list-col" style="width:25%;">
													{{ ucfirst(@$sum->mate->firstname) }} {{ ucfirst(@$sum->mate->lastname) }}
												</div>
											@else
												<div class="list-col" style="width:25%;">
												{{ ucfirst(@$sum->user->firstname) }} {{ ucfirst(@$sum->user->lastname) }}
											</div>
											@endif
											<div class="list-col" style="width:35%;">
												<?php $img_url = getSportsImageBySportsID( @$sum->event->sports->id ); ?>
												<img class="sport-icon" src='{{ url("$img_url") }}' style="display:inline-block"/>
												<div class="match">
												<span>{{ @$sum->event->name }}</span>
												<p>
													<i aria-hidden="true" class="fa fa-calendar"></i>
													{{ showDateTime(@$sum->event->startdatetime,@$sum->event->timezone, "D d M h:i A") }}
												</p>
												</div>
											</div>
											<div class="list-col" style="width:25%;">
												<p>{{ @$sum->wager_amount }}</p>
											</div>
											<?php 
												$status 	= '';
												if($sum->challenge_status == 'awaiting'){
													$status = 'Awaiting Acceptance';
												}
												if($sum->challenge_status == 'won' || $sum->challenge_status == 'lost'){
													if(Auth::user()->id == $sum->winner_user_id){
														$status = 'won';
													}else{
														$status = 'lost';
													}
												}
												if($sum->challenge_status == 'pending'){
													$status = 'Pending';
												}
												if($sum->challenge_status == 'rejected'){
													$status = 'Rejected';
												}
												if(@$sum->challenge_status == 'cancelled'){
													$status = 'Cancelled';
												}
												if(@$sum->challenge_status == 'active'){
													$status = 'Active';
												}
											?>
											<div class="list-col text-right" style="width:15%;">
												<div style="width:30px;">{{ ucwords(@$status) }}</div>
											</div>
										</a>
									@endforeach
								</div>
							@else
								<div class="mate no_record_found" style="text-align: center; margin-top:14px;">Sorry! No record found.</div>
							@endif
						</div>
					</div>					
				</div>
 			</div>
 			<div class="no_record_found text-center notty-loader" id="loader" style="display:none; margin-bottom: -11px; margin-top: -6px;">
					<img src="{{ url('assets/users/img/pre_loader.gif') }}"/>
				</div>
				<div class="challenge_msg no_record_found text-center notty-loader" style="display:none; float:none; margin-bottom: 0 !important; margin-top: 0px !important;" id="no-more-folk">
					<b>That's all.</b>
				</div>
 		</div>
 	</div>
 	<style>
		.list-col img
		{
			margin-left:-28px;
			float:left;
		}
		.list_outer h1 select {
		width: 177px !important;
		}	
		#status-filter {
			background: none repeat scroll 0 0 #eee;
			border: 1px solid #ddd;
			border-radius: 2px;
			padding: 3px 6px;
			width: 200px;
		}

		.list-row {
			cursor: pointer !important;
		}

 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
<script type="text/javascript">
	var page_number = 1;
	var loading = false;
	$(document).ready(function(){
		$(document).off('change','#status-filter').on('change','#status-filter',function(e){
			e.preventDefault();
			var filter = $(this).val();
			$.ajax({
				type:'get',
				url: siteurl+'/wagers',
				data : { "filter" : filter },
				dataType:'text',
				success: function(response) {
					if( response ) {
						$('.my-wagers').html('');
						$('.my-wagers').html(response);
						$('#no-more-folk').css('display','none');
						page_number = 1;
						loading = false;
					} else {
						return false;
					}
				}
			});
		});
			
	// load more mates on scroll
	
	
	$(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
			var wagerlen = $('.wagerlen').length;
			if ( wagerlen ) {
				if(loading == false){
					page_number++; //page number increment
					loading = true;  //set loading flag on
					var filter = $('#status-filter').val();
					var url = siteurl+'/wagers';
					var data = { "page_number" : page_number, "filter" : filter, "pagination":"true"  } 
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
								$('.load-more-wagers').append(response);
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
	
			
		});
	</script>
@endsection
