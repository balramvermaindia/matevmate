<div class="steps step3">
	<span class="sel_step"><i class="fa fa-group"></i>Choose Team</span>
	<span class="sel_step"><i class="fa fa-user"></i>Select Mate</span>
	<span class="sel_step"><i class="fa fa-beer"></i>Pick Wager</span>
	<span><i class="fa fa-file-text"></i>Place Wager</span>
</div>

<div class="list-row search-filter">
	<div class="search">
		<input type="text" placeholder="Search for Product Name" name="needle" id="needle" value="<?php if( isset($_POST['needle']) && !empty($_POST['needle']) ) echo $_POST['needle']; ?>">
	</div>
	<div style="width:40%;" class="search">
		<a class="more_filter search_submit" href="javascript:void(0)" id="search_btn">Search</a>
	</div>
</div>
<?php

	$challengeMate	= Session::get('challenMate');
	$selectedMateCount 	= count($challengeMate['selectedMates']);
	
?>

<div class="mates_outer">
<?php
	if ( count($wager) ) {
		foreach ( $wager as $item ) {
			$name = @$item->name;
?>			 
	<div class="mate product">
		<div class="mate_detail product_detail" style="padding:0px;">
			<div class="mate_name product_name">{{ $name }}</div>
		</div>
		
		<div class="wager">
			<img src="{{ url('assets/shop/products/'.$item->image) }}"/>
		</div>
		
		<div class="bets prize"><span>Prize</span>${{ $item->price }}</div>
		@if($selectedMateCount > 1)
			<a class="pull-right select_wager" href="javascript:void(0);" style="margin-top:0px;" data-toggle="modal" data-target="#myModal" data-wager="{{ $item->id }}" data-backdrop="static">SELECT WAGER</a>
		@else
			<a class="pull-right sel-wager" href="javascript:void(0);" style="margin-top:0px;" data-wager="{{ $item->id }}">SELECT WAGER</a>
		@endif
	</div>
<?php
	}
} else {
?>
	<div class="mate" style="text-align: center; width:100% !important;">No Record Found.</div>
<?php
}
?>	
</div>
<div class="no_record_found text-center notty-loader" id="loader" style="display:none; margin-bottom: -11px; margin-top: -24px;"><img src="{{ url('assets/users/img/pre_loader.gif') }}"/></div>
<div class="challenge_msg no_record_found text-center notty-loader" style="display:none; float:none; margin-bottom: 0 !important; margin-top: -18px !important;" id="no-more-folk"><b>That's all.</b></div>
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	   
	   <div class="modal-dialog">
		  <div class="modal-content">
			 
			 <div class="modal-header">
				<button type="button" class="close popUpClose" data-dismiss="modal" aria-hidden="true">
				   ×
				</button>
				
				<h4 class="modal-title" id="myModalLabel">
				   Select Wager For Mate
				</h4>
			 </div>
			 
			 <div class="modal-body">
				 <h4>
				   This Wager is for : 
				</h4>
				<input type="radio" id="all" class="userSelector" name="userSelector" value="all" />All
				<?php  if(isset( $challengeMate['selectedMates'] ) && count($challengeMate['selectedMates'])>1) { ?>
					<input type="radio" class="userSelector" name="userSelector" value="individual" />Individual
				<?php } ?>
				<div class="listOfUsers" style="display:none;">
					<div class="list_outer outcome_outer mateContainer" style="clear: both;display: table;vertical-align: top;width: 100%;">
						<div id="mainMateContainer"></div>
						
						
					</div>
				</div>
			 </div>
			 
			 <div class="modal-footer">
				 <div id="buttonsContainer">
					<button type="button" class="btn btn-default popUpClose" data-dismiss="modal">
					   Close
					</button>
					
					<button type="button" class="btn btn-primary" id="finalizeWager">
					   Save
					</button>
				</div>
				<div id="continueContainer" style="display:none;">
					<button type="button" class="btn btn-default accpet_btn" data-dismiss="modal">
					   Continue
					</button>
				</div>
			</div>
			 
		  </div><!-- /.modal-content -->
	   </div><!-- /.modal-dialog -->
	   
	</div><!-- /.modal -->



 
@section('script')
<script>

var wager_id;
//$('#all').prop('checked', true);
var allSelected = false;

$(document).off('click', '.select_wager').on('click', '.select_wager', function(e) {
	
	var inthtmlData = '';
	inthtmlData += '<div id="mainMateContainer"></div>';
	$( "div#mainMateContainer" ).replaceWith( inthtmlData );
	
	wager_id 	= $(this).attr('data-wager');
	var data 	= {"_token" : "{{ csrf_token() }}", "wager_id" : wager_id};
	$.ajax({
		url		: siteurl + "/assign/wager",
		type	: 'POST',
		data	: data,
		dataType: 'text',
		success: function(response) {

			if (response) {
				var obj = $.parseJSON(response);
				totalUsers = obj.length;
				var htmlData = '';
				htmlData += '<div id="mainMateContainer">';
				if (obj.length) {
					
					for(x in obj){
						console.log(obj[x].id);
						
						htmlData += '<div class="list-row"><div style="width:190px;" class="list-col text-left"><span style="position:relative;top:7px;" id="sel_mate_name">'+obj[x].firstname+' '+ obj[x].lastname+'</span></div><div class="list-col cmts matchs_btn text-right"><a class="select_mate sel_mate pop_btn" data-user='+obj[x].id+' href="javascript:void(0);"style="margin:0px;visibility:visible"><i class="fa fa-check" ></i> SELECT MATE</a></div></div>';
						
						$( "div#mainMateContainer" ).replaceWith( htmlData );
					}
				}
				htmlData += '</div>';
			}
		},
		error: function(){
			
		}
	});
	
	$('#myModal').modal('show');
});

$(document).off('click', '#finalizeWager').on('click', '#finalizeWager', function(e) {
	var selectedUser = [];
	var all = $('#mainMateContainer').children('.list-row').each(function() {
		var selAnchor = $(this).children('div.matchs_btn').children('a.pop_btn_sel');
		if (selAnchor.length) {
			var dataUser = selAnchor.attr('data-user');
			selectedUser.push(dataUser);
		}
	}) ;
	
	var data 	= {"_token" : "{{ csrf_token() }}", "wager_id" : wager_id, "selUser": selectedUser};
	
	$.ajax({
		url:  siteurl + "/apply/wager",
		type: 'POST',
		data	: data,
		dataType: 'text',
		success: function(response) {
			if (response && response != 'false') {
				$('#myModal').modal('hide');
			} else {
				generate('error', 'An unknown error occured. Please reload the page to continue');
			}
		}
	});
	
});


$(document).off('click', '.select_mate').on('click', '.select_mate', function(e) {
	e.preventDefault();
	$(this).toggleClass('pop_btn_sel');
	
	var currentSelectedUser	= $('.pop_btn_sel').length;

	if ( currentSelectedUser == totalUsers ) {
		allSelected = true;
		$('#continueContainer').css('display','block');
		$('#buttonsContainer').css('display','none');
	} else {
		allSelected = false;
		$('#continueContainer').css('display','none');
		$('#buttonsContainer').css('display','block');
	}
});

$(document).off('click', '.popUpClose').on('click', '.popUpClose', function(e) {
	e.preventDefault();
	
	$('.sel_mate').removeClass('pop_btn_sel');
});


$(document).off('click', '.userSelector').on('click', '.userSelector', function(e) {
	var radioValue = $(this).attr('value');
	if ( radioValue == 'all' ) {
		allSelected = true;
		$('.sel_mate').addClass('pop_btn_sel');
		$('#continueContainer').css('display','block');
		$('#buttonsContainer').css('display','none');
		$('.listOfUsers').css('display', 'none');
		$('.sel_mate').removeClass('pop_btn_sel');
	} else {
		allSelected = false;
		$('#continueContainer').css('display','none');
		$('#buttonsContainer').css('display','block');
		$('.listOfUsers').css('display', 'block');
	}
});


$(document).off('click', '.accpet_btn').on('click', '.accpet_btn', function(e) {
	e.preventDefault();
	
	
	if ( allSelected ) {
		
		var radioValue = $('.userSelector').attr('value');
		var selectedUserFinal = [];
		
		if ( radioValue == 'all' ) {
			var allFinal = $('#mainMateContainer').children('.list-row').each(function() {
			var selAnchorFinal = $(this).children('div.matchs_btn').children('a.pop_btn');
			if (selAnchorFinal.length) {
				var dataUserFinal = selAnchorFinal.attr('data-user');
				selectedUserFinal.push(dataUserFinal);
				}
			}) ;
		} else {
			var allFinal = $('#mainMateContainer').children('.list-row').each(function() {
			var selAnchorFinal = $(this).children('div.matchs_btn').children('a.pop_btn_sel');
			if (selAnchorFinal.length) {
				var dataUserFinal = selAnchorFinal.attr('data-user');
				selectedUserFinal.push(dataUserFinal);
				}
			}) ;
		}
		
		
		

		var data 	= {"_token" : "{{ csrf_token() }}", "wager_id" : wager_id, "selUser": selectedUserFinal};
		$.ajax({
			url:  siteurl + "/review/wager",
			type: 'POST',
			data	: data,
			dataType: 'text',
			success: function(response) {
				$('#myModal').modal('hide');
				if (response && response != 'false') {
					$('#steps_main_container').html();
					$('#steps_main_container').html(response);
				} else {
					
					generate('error', 'An unknown error occured. Please reload the page to continue');
				}
			}
		});
	} else {
		generate('error', 'Please select a wager to continue');
	}
});

$(document).off('click','.sel-wager').on('click','.sel-wager',function(e){
	e.preventDefault();
	var wager_id = $(this).attr('data-wager');
	var selectedUserFinal = [];
	selectedUserFinal = <?php echo json_encode($challengeMate['selectedMates']); ?>;
	if(wager_id){
		var data 	= {"_token" : "{{ csrf_token() }}", "wager_id" : wager_id, "selUser": selectedUserFinal};
		$.ajax({
			url:  siteurl + "/review/wager",
			type: 'POST',
			data	: data,
			dataType: 'text',
			success: function(response) {
				
				if (response && response != 'false') {
					$('#steps_main_container').html();
					$('#steps_main_container').html(response);
				} else {
					generate('error', 'An unknown error occured. Please reload the page to continue');
				}
			}
		});
	}else{
		generate('error', 'Please select a wager to continue');
	}
	
});
// load more wagers on scroll
var loading = false;
var page_number = 1;
$(document).ready(function(){
	
	$(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
			var productlen = $('.product').length;

			if ( productlen ) {
				if ( loading == false ) {
					page_number++; //page number increment
					loading = true;  //set loading flag on
					var url = siteurl+'/select/wager';
					var needle = $('#needle').val();
					//$('#no-more-folk').css('display','none');
					var data = {"page_number":page_number , "pagination":"true" ,"needle":needle, "_token" : "{{ csrf_token() }}"};

					$.ajax({
						url: url,
						type: 'post',
						data: data,
						dataType: 'text',
						async: true,
						beforeSend: function() {
							$('#loader').show();
						},
						complete: function(){
							$('#loader').hide();
						},
						success: function(response){
							loading = false;
							if ( response != "false" ) {
								$('.mates_outer').append(response);
							} else {
								loading = true;
								//$('#no-more-folk').css('display','block');
							}
						}
					});
				}
			} else {
				return false;
			}
		}
	});	
	
	
	$(document).off('click','#search_btn').on('click','#search_btn',function(e){
		e.preventDefault();
		var needle = $('#needle').val();
		var data = { "needle": needle,"filters": "true", "_token": "{{ csrf_token() }}" };
		var url = siteurl+'/select/wager';
		$.ajax({
			
			type:'post',
			url: url,
			data: data,
			dataType: 'text',
			success: function(response){
				if(response){
					$('.mates_outer').html('');
					$('.mates_outer').html(response);
					loading = false;
					page_number = 1;
				} else {
					return false;
				}
			}
			
		});
	});
	
});

$(document).off('keypress','#needle').on('keypress','#needle',function(e){
	var key = e.which;
	if( key == 13 ) {
		$('#search_btn').trigger('click');
	} 
});


</script>
