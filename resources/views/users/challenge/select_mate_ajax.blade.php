<div class="steps step2">
	<span class="sel_step"><i class="fa fa-group"></i>Choose Team</span>
	<span class="sel_step"><i class="fa fa-user"></i>Select Mate</span>
	<span><i class="fa fa-beer"></i>Pick Wager</span>
	<span><i class="fa fa-file-text"></i>Place Wager</span>
</div>


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
			<select class="text" style="margin-bottom:0px;" id="bet_filter" name="bet_filter">
				<option value="">Any betting</option>
				<option value="10">Atleast 10 bets</option>
				<option value="20">Atleast 20 bets</option>
				<option value="30">Atleast 30 bets</option>
				<option value="40">Atleast 40 bets</option>
			</select>
		</div>
	</div>
</div>




<div class="mates_outer">
<?php
	if ( count($mates) ) {
		foreach ( $mates as $mate ) {
			$name = @$mate->firstname . ' ' .@$mate->lastname;
?>
	<div class="mate matelen">
		<div class="mate_detail">
			<div class="cmt_pic" style="left:0px;">
				@if ( null != @$mate->photo )
					<img class="avatar" src="{{ url('assets/users/img/user_profile/'.@$mate->photo) }}">
				@else
					<img class="avatar" src="{{ url('assets/img/cmt.png') }}">
				@endif
			</div>
			<div class="mate_name"><?php echo ucwords(@$name); ?></div>
			<div class="stars"><img src="{{ url('assets/img/stars.png') }}"/></div>
			<div class="bets"><span>{{ @$mate->total_wagers }}</span>@if(@$mate->total_wagers == '0' || @$mate->total_wagers == '1') Wager @else Wagers @endif</div>
			<div class="bets_divide"><span style="color:#019c6b;">{{ @$mate->wagers_won }} win </span> / <span style="color:#ba4c4c;">{{ @$mate->wagers_lost }} lost</span></div>
		</div>
		<a href="{{ url('user-profile/' . $mate->id) }}" target="_blank">VIEW PROFILE</a>
		<a class="pull-right select_mate @if( @$passedMateID == @$mate->id ) mate-selected @endif" data-user="{{ $mate->id }}" href="javascript:void(0);"><i class="fa fa-check" ></i> SELECT MATE</a>
	</div>
<?php
	}
} else {
?>
	<div class="mate" style="text-align: center; width:100% !important;">No record found.</div>
<?php
}
?>
 </div>
<div class="no_record_found text-center notty-loader" id="loader" style="display:none; margin-bottom: -11px;
		margin-top: -24px;"><img src="{{ url('assets/users/img/pre_loader.gif') }}"/></div>
<div class="challenge_msg no_record_found text-center notty-loader" style="display:none; float:none; margin-bottom: 0 !important; margin-top: -18px !important;" id="no-more-folk"><b>That's all.</b></div>


@section('script')
<script type="text/javascript">
var mate_arr=[];
var loading = false;
var page_number = 1;
$(document).ready(function(){
	$('a.mate-selected').each(function(){
		var mate_id = $(this).attr('data-user');
		if(mate_arr.indexOf(mate_id)  == -1){
			mate_arr.push(mate_id);
		}
	});
	
	// load more mates on scroll
	$(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
			var mateslen = $('.matelen').length;

			if ( mateslen ) {
				if ( loading == false ) {
					page_number++; //page number increment
					loading = true;  //set loading flag on
					var url = siteurl+'/select/mate';
					var needle = $('#needle').val();
					//$('#no-more-folk').css('display','none');
					var data = {"page_number":page_number , "pagination":"true" ,"needle":needle};

					$.ajax({
						url: url,
						type: 'get',
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
	
	//trigger serach button click event on pressing enter key
	
	$(document).off('keypress','#needle').on('keypress','#needle', function(e){
		var key = e.which;
		if ( key==13 ) {
			$('#search_btn').trigger('click');
		}
	});
	
});

$(document).off('click', '.select_mate').on('click', '.select_mate', function(e) {
	e.preventDefault();
	var mate_id= $(this).attr('data-user');
	if(mate_arr.indexOf(mate_id)  == -1){
		mate_arr.push(mate_id);
	}else{
		var index = mate_arr.indexOf(mate_id);
		mate_arr.splice(index, 1);
		
	}

	$(this).toggleClass('mate-selected');
});

$(document).off('click', '.accpet_btn').on('click', '.accpet_btn', function(e) {
	e.preventDefault();
	if ( mate_arr.length > 0 ) {
		
		var data 	= {"_token" : "{{ csrf_token() }}", "mate_arr" : mate_arr};
		$.ajax({
			url:  siteurl + "/select/wager",
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
	} else {
		generate('error', 'Please select atleast one mate to continue');
	}
});

$(document).off('click','#search_btn').on('click','#search_btn',function(e){
	e.preventDefault();
	var needle = $('#needle').val();
	
	var data = { "needle":needle, "filters": "filters", "mate_arr" : mate_arr };
	if( data ) {
		$.ajax({
			type:'get',
			url: siteurl +"/select/mate",
			data: data,
			dataType:'text',
			success: function(response){
				if( response ) {
					$('.mates_outer').html('');
					$('.mates_outer').html(response);
					loading = false;
					page_number = 1;
				} else {
					return false;
				}
			}
	  });
	} else {
		return false;
	}
});

$(document).off('click','#more_filter').on('click','#more_filter' , function() {
	$('#filter_popup').toggle();
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
</script>

