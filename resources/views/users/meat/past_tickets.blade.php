@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
			<div class="list_outer outcome_outer meat_tray">
 				<h1><span>Meat Tray Raffle</span></h1>
 				<a class="pull-right btn accpet_btn" style="color:#fff;margin:5px;padding:6px 15px;font-family:ubuntumedium;font-size:14px;" href="javascript:void(0)" id="how-it-works">How It Works</a>
				<div class="profile_title" style="margin:10px">
					<span>My Tickets</span>
					<a href="#" class="btn_blue pull-right green">Next Drawn on: 26 June 2016</a>
				</div>
	 			<div style="padding:10px;">
					<div class="raffle_left">
						@include('sections.meatTrayTabs')
<!--
						<ul class="sub_tabs" style="display:block;">
								<li class="sel-sub-tab"><a href="javascript:void(0)" id="my-current-tickets">My Current Tickets</a></li>
								<li class=""><a href="javascript:void(0)" id="my-past-tickets">My Past Tickets</a></li>
						</ul>
-->

		 				<div class="list_outer" id="past-tickets">
							<div class="list-row">
								<div style="width:33%;" class="list-col">
									#5678135486
								</div>
								<div style="width:33%;" class="list-col text-right">
									Drawn on: 26 June 2016								
								</div>
							</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 					<div class="list-row">
		 						<div style="width:33%;" class="list-col">
		 							#5678135486
		 						</div>
		 						<div style="width:33%;" class="list-col text-right">
		 							Drawn on: 26 June 2016								
		 						</div>
		 					</div>
		 				</div>
		 			<div class="list_outer" style="margin:10px 0px;">
 					<h1><span>Last Drawn Results</span></h1>
 					<div class="list-row list-title">
 						<div class="list-col" style="width:33%;">Drawn Date</div>
 						<div class="list-col" style="width:33%;">Winner</div>
 						<div class="list-col" style="width:33%;">Ticket Number</div>
 					</div>
 					<div class="list-row">
 						<div class="list-col" style="width:33%;">
 							26 June 2016
 						</div>
 						<div class="list-col" style="width:33%;">
 							Ajay Verma
						</div>
						<div class="list-col" style="width:33%;">
 							#5648964126
						</div>
 					</div>
 					<div class="list-row">
 						<div class="list-col" style="width:33%;">
 							26 June 2016
 						</div>
 						<div class="list-col" style="width:33%;">
 							Ajay Verma
						</div>
						<div class="list-col" style="width:33%;">
 							#5648964126
						</div>
 					</div>
 					<div class="list-row">
 						<div class="list-col" style="width:33%;">
 							26 June 2016
 						</div>
 						<div class="list-col" style="width:33%;">
 							Ajay Verma
						</div>
						<div class="list-col" style="width:33%;">
 							#5648964126
						</div>
 					</div>
 					<div class="list-row">
 						<div class="list-col" style="width:33%;">
 							26 June 2016
 						</div>
 						<div class="list-col" style="width:33%;">
 							Ajay Verma
						</div>
						<div class="list-col" style="width:33%;">
 							#5648964126
						</div>
 					</div>
 					<div class="list-row">
 						<div class="list-col" style="width:33%;">
 							26 June 2016
 						</div>
 						<div class="list-col" style="width:33%;">
 							Ajay Verma
						</div>
						<div class="list-col" style="width:33%;">
 							#5648964126
						</div>
 					</div>
 					<div class="list-row">
 						<div class="list-col" style="width:33%;">
 							26 June 2016
 						</div>
 						<div class="list-col" style="width:33%;">
 							Ajay Verma
						</div>
						<div class="list-col" style="width:33%;">
 							#5648964126
						</div>
 					</div>
 				</div>
	 		</div>
	 		
	 		<div class="raffle_right">
	 			<div class="information">
	 				<h1>Prize Details</h1>
	 				<div class="info_inner">
	 					<img src="{{ url('assets/users/img/meat.png') }}"/>
	 					<p>
						The Winner of our Meat Tray Raffle will receive a mixed tray of top quality, fresh meat, delivered directly from the supplier to their door. The tray will vary every draw, but may include items such as: Rump, t-bone or scotch fillet steaks, rissoles, beef or chicken kebabs, chicken, beef or pork sausages, burger patties, rashers of bacon, roasts, mince, poultry pieces or whole birds or even eggs! 
	 					</p>
	 				</div>
	 			</div>
	 			
	 			<div class="information">
	 				<h1>Delivery Information</h1>
	 				<div class="info_inner">
	 					<p>
						Your delicious Meat Tray will be delivered 2 working after days after the raffle is drawn for most areas. Our Courier Service that delivers your prize use refrigerated trucks to ensure your meat arrives as fresh as it left the butcher. We cannot guarantee delivery times, however, if you are not home at the time of delivery your order will be left for you at the door in a cold carton with ice. If you cannot be at home at the time of delivery, we also do deliveries to work places.
</p>
	 				</div>
	 			</div>

	 		</div>
	 		
	 		
	 		
	 		<div class="list_outer" style="margin:10px 0px;">
 					<h1><span>Reviews from Winners</span></h1>
 					
 					<div class="list-row">
 						<div class="list-col" style="width:40px;">
 							<div class="small_profile">
	 							<img src="{{ url('assets/users/img/user.png') }}">
	 						</div>
 						</div>
 						<div class="list-col coments">
 							<h5>Rahid Khan <span>Date: 26 June 2016</span></h5>
 							<p>Thank you Mate V Mate for my amazing meat tray. It arrived on time and was cold and fresh. The quality of the meat was outstanding and I was very impressed with how much meat was on it!</p>
						</div>
 					</div>
 					
 					<div class="list-row">
 						<div class="list-col" style="width:40px;">
 							<div class="small_profile">
	 							<img src="{{ url('assets/users/img/user.png') }}">
	 						</div>
 						</div>
 						<div class="list-col coments">
 							<h5>Rahid Khan <span>Date: 26 June 2016</span></h5>
 							<p>My family absolutely loved the range of meat that arrived at our front door. It had something for everyone. My husband loved the steaks, I thought the roast was great and the kids raved about the sausages.</p>
						</div>
 					</div>
 					
 					<div class="list-row">
 						<div class="list-col" style="width:40px;">
 							<div class="small_profile">
	 							<img src="{{ url('assets/users/img/user.png') }}">
	 						</div>
 						</div>
 						<div class="list-col coments">
 							<h5>Rahid Khan <span>Date: 26 June 2016</span></h5>
 							<p>Better than any pub meat tray! There was a great variety of meat and the cuts were lovely. The quantity, quality and ease of delivery were fantastic. Thank you Mate V Mate!</p>
						</div>
 					</div>
 					
 			</div>		
 		</div>
 	</div>
</div>
<style>
.modal-footer{
	display:none;
}
.modal-body {
    text-align: left;
}
</style>
@endsection
@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			
			$(document).on('click','#how-it-works', function(){
				var text = "1: Every time you place a wager or accept a wager from a mate, you will receive tickets to the upcoming draw. Your tickets are valid whether you win lose or draw the match.<br/><br/>2: A ticket number will be drawn at random on the date specified for that draw.<br/><br/>3: The winner will be notified via their ‘Notifications’ page and given directions as to how to organise their delivery.<br/><br/>It’s that simple!"
				$.confirm({
					text: text,
					title: 'How It Works',
					dialogClass: "modal-dialog modal-lg custom-confirm-matevmate", // Bootstrap classes for large modal
					className: "medium"
				});
			});
		})
	</script>
@endsection
