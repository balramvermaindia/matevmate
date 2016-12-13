<div class="steps step3">
	<span class="sel_step"><i class="fa fa-group"></i>Choose Team</span>
	<span class="sel_step"><i class="fa fa-user"></i>Select Mate</span>
	<span class="sel_step"><i class="fa fa-beer"></i>Pick Wager</span>
	<span><i class="fa fa-file-text"></i>Place Wager</span>
</div>

<!--
<div class="list-row search-filter">
	<div class="search">
		<input type="text" placeholder="Search for Product Name" name="needle" id="needle" value="<?php if( isset($_POST['needle']) && !empty($_POST['needle']) ) echo $_POST['needle']; ?>">
	</div>
	<div style="width:40%;" class="search">
		<a class="more_filter search_submit" href="javascript:void(0)" id="search_btn">Search</a>
	</div>
</div>
-->


<div class="mates_outer mate no-wager-bg" style="padding:20px 3%;margin-bottom:0px;">
	<div class="voucher">Please select a MvM Voucher for the amount you wish to wager:</div>		 
	<a class="product wager1" id="wager_amt1" href="javascript:void(0)" data-val="20">
		<div class="wager-prize-outer" >
			<div class="wager-prize-row">
				<div class="wager-prize-col"><i class="fa fa-check"></i> AUD 20.00</div>
			</div>
		</div>
	</a>
	
	<a class="product wager1" id="wager_amt2" href="javascript:void(0)" data-val="30">
		<div class="wager-prize-outer">
			<div class="wager-prize-row">
				<div class="wager-prize-col"><i class="fa fa-check"></i> AUD 30.00</div>
			</div>
		</div>
	</a>
	
	<a class="product wager1" id="wager_amt3" href="javascript:void(0)" data-val="50">
		<div class="wager-prize-outer">
			<div class="wager-prize-row">
				<div class="wager-prize-col"><i class="fa fa-check"></i> AUD 50.00</div>
			</div>
		</div>
	</a>
	
	<a class="product wager1" id="wager_amt4" href="javascript:void(0)"  data-val="100">
		<div class="wager-prize-outer">
			<div class="wager-prize-row">
				<div class="wager-prize-col"><i class="fa fa-check"></i> AUD 100.00</div>
			</div>
		</div>
	</a>
	
	<a class="product wager1 custom" id="wager_amt5" href="javascript:void(0)"  data-val="100">
		<div class="wager-prize-outer">
			<div class="wager-prize-row">
				<div class="wager-prize-col"><i class="fa fa-check"></i> Custom</div>
			</div>
		</div>
	</a>
	
	<a class="product wager1" href="javascript:void(0)" id="text-box-div" style="display:none;"  data-val="100">
		<div class="wager-prize-outer">
			<div class="wager-prize-row">
				<div class="wager-prize-col"><input type="text" class="text-box" placeholder=" Enter Amount e.g. 70.00"/></div>
			</div>
		</div>
	</a>
	</div>
	<div style="text-align: right; clear: both; padding:0px 3%; ">
	<div style="margin:4px 0px;float:none;display:inline-block;clear:both;" class="cmts continue accpet_btn"> <a href="javascript:void(0);" id="contine_and_accept" class="accpet_btn" style="display:inline-block;float:none;">Continue</a></div>
	</div>
	<div class="note">
		<h5>Note:</h5>
		- MvM Vouchers are redeemable for any product via the MvM Shop. <br>
		- MvM Vouchers are non transferable and must redeemed within 90 days period. </div>
	

<!--
<div>
	<div class="mates_outer mate no-wager-bg">
	<div class=" product wager1">
		<div class="wager-prize-outer">
			<div class="wager-prize-row">
				<div class="wager-prize-col"><input type="checkbox" class="check-box" /></div>
				<div class="wager-prize-col" style="padding:0px 10px; text-align:left;">Custom</div>
				<div class="wager-prize-col" style="visibility:hidden;" id="text-box"><input type="text"/></div>
			</div>
		</div>
	</div>
	</div>
</div>
-->

	
@section('script') 
<script type="text/javascript">
	var wager_amount = '';
	$(document).ready(function(){
		$(document).on('click','#wager_amt1, #wager_amt2, #wager_amt3, #wager_amt4, #wager_amt5', function(e){
			e.preventDefault();
			var cls = $(this).hasClass('mate-selected');
			if( !cls ) {
				$('#wager_amt1').removeClass('mate-selected');
				$('#wager_amt2').removeClass('mate-selected');
				$('#wager_amt3').removeClass('mate-selected');
				$('#wager_amt4').removeClass('mate-selected');
				$('#wager_amt5').removeClass('mate-selected');
				$(this).addClass('mate-selected');
				
				if( $(this).hasClass('custom') ) {
					$('#text-box-div').css('display','inline-block');
				} else {
					$('#text-box-div').css('display','none');
				}
			} 
			
		});
		
		$(document).off('click', '.accpet_btn').on('click', '.accpet_btn', function(e) {
			e.preventDefault();
			if( $('.mate-selected').hasClass('custom') ) {
				 wager_amount = $('input.text-box').val();
			} else {
				 wager_amount = $('.mate-selected').attr('data-val');
			}
			
			if ( wager_amount.length > 0 ) {
				
				var data 	= {"_token" : "{{ csrf_token() }}", "wager_amount" : wager_amount};
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
			} else {
				generate('error', 'Please select wager to continue');
			}
		});
		
	});


</script>

