@extends('layouts.app')
@extends('sections.headerInnerSection')

@section('content')
<!-- Header -->
<div class="heading">
	
 		<h1>How it Works</h1>
 		<p>Challenge you mates on your favourite sports. Wager with beverages and secure those all important bragging rights!</p>
 	</div>
 	<section class="how_work">
 		<div class="container">
 			<div class="row">
 				<div class="col-md-6 works">
 					<h1>1.</h1>
 					<h3>Create Your Profile</h3>
 					<p>Simply click on the ‘Sign Up’ link on the home page to create your own profile and become a registered member of Mate V Mate. There is no fee and no contract to join.Your profile will include your personal details to ensure fast delivery or your winnings.On your ‘Profile Page’ you will be able to update your information and contact details at any time. This page will also show which sports and teams you follow and support.Other members will not have access to any of your personal information. What they will have access to is your statistics for winning and losing wagers, The results of your last three bets, your ‘star rating’ which shows how well you honour your bets, and your list of top sports and teams you like to wager on. It will also show them your preferences for the items you like to wager with.
 					<br/>
 					You will also find your <a href="{{ url('meat-tray-raffle') }}">Meat Tray Raffle</a> tickets here and it will list any past raffles you have won.</p>
 				</div>
 				<div class="col-md-6">
 					<img src="{{ URL::to('assets/img/h1.png') }}" style="margin-top:40px;"/>
 				</div>
 			</div>
 		</div>
 	</section>
 	
 	<section class="how_work">
 		<div class="container">
 			<div class="col-md-6">
 					<img src="{{ URL::to('assets/img/h2.png') }}"/>
 			</div>
 			<div class="row">
 				<div class="col-md-6 works">
 					<h1>2.</h1>
 					<h3>Invite Your Mates to Join</h3>
 					<p>To invite your mates to join Mate V Mate, you can search for them using your Facebook, Twitter, Instagram and Snapchat friends/followers lists. You send them an invitation to become your Mate and once they have registered you will receive a notification saying they’ve registered.</p>
 				</div>
 			</div>
 		</div>
 	</section>
 	
 	<section class="how_work">
 		<div class="container">
 			<div class="row">
 				<div class="col-md-6 works">
 					<h1>3.</h1>
 					<h3>Select your Sporting Event</h3>
 					<p>By clicking on the <a href="{{ url('sports') }}">Sports</a> link on the Home page or the <a href="{{ url('sports') }}">All Sports</a> link on the dashboard, you are able to view all the sports available for to you place wagers on. Within each sport you will see the different leagues, championships, countries available to bet on. After selecting one of these, you will then have the upcoming games or matches to view and select, to challenge a mate to a wager.Alternatively, on the <a href="{{ url('dashboard') }}">Dashboard</a> you can see upcoming events for your teams/sports of interest at a single glance.</p>
 				</div>
 				<div class="col-md-6">
 					<img src="{{ URL::to('assets/img/h3.png') }}"/>
 				</div>
 			</div>
 		</div>
 	</section>
 	
 	<section class="how_work">
 		<div class="container">
 			<div class="col-md-6">
 					<img src="{{ URL::to('assets/img/h4.png') }}"/>
 			</div>
 			<div class="row">
 				<div class="col-md-6 works">
 					<h1>4.</h1>
 					<h3>Choose Your Wager from the Shop</h3>
 					<p>Once you have selected the game you want to place a bet on, you will be directed to the <a href=" {{ url('shop') }}">Shop</a> to choose the item you wish to use for the wager. There are many categories to choose from, like beer, wine and champagne to spirits, mixed drinks and ciders. All of your favourite brands are available and the prices for each are listed with each item. Whatever your favourite drink is, you will find it in the shop. Once you’ve had a browse, simply select the item you wish to wager with, and you’re ready to challenge your mate to the wager.</p>
 				</div>
 			</div>
 		</div>
 	</section>
 	
 	<section class="how_work">
 		<div class="container">
 			<div class="row">
 				<div class="col-md-6 works">
 					<h1>5.</h1>
 					<h3>Challenge Your Mate</h3>
 					<p>If you have decided to challenge somebody via the ‘Banter Board’ you will not need to complete this step.<br/>
					You have chosen your sporting event or match, you’ve chosen the item you will be wagering with from the shop, now you need to send the challenge to your Mate. Once you’ve completed your selection in the shop you will be redirected to a page that will list all of your mates. You simply select an existing mate from this list and hit the ‘Send Challenge’ button and wait to see if they accept or decline the wager. You will receive a notification letting you know if they have a back bone or not. If you wish to challenge somebody who is not a registered Mate, you can search for them using your Facebook, Twitter, Instagram and Snapchat friends/followers lists. You send them an invitation to become your Mate and once you receive a message saying they’ve registered, you can send them the challenge.</p>	
 				</div>
 				<div class="col-md-6">
 					<img src="{{ URL::to('assets/img/h5.png') }}"/>
 				</div>
 			</div>
 		</div>
 	</section>
 	
 	<section class="how_work">
 		<div class="container">
 			<div class="col-md-6">
 					<img src="{{ URL::to('assets/img/h6.png') }}"/>
 			</div>
 			<div class="row">
 				<div class="col-md-6 works">
 					<h1>6.</h1>
 					<h3>Honour Your Wager or Relax and Wait for your Winnings</h3>
 					<p>You will receive a notification when the sporting event has concluded, notifying you of the results. The winners of the wagers have a choice of convenient delivery or pickup options across Australia. If you have lost, in order to honour your wager, simply complete your transaction in the <a href="{{ url('shop') }}">Shop,</a> we’ll do the rest. If the event results in a draw, you do not need to do a thing.</p>
 				</div>
 			</div>
 		</div>
 	</section>



 
 	<footer>
    <div class="container">
		<div class="row">
			<div class="col-lg-5">
				<h1>About Mate V Mate</h1>
				<p>Mate V Mate was established in 2011 after a sporting enthusiast got frustrated with their mates not honouring bets they had made on different sporting matches and events.
				<br/> 
                Sure, if they were at their favourite drinking hole it was easy enough to make sure that mate bought them the beer they’d owed them, but when wagers were placed on games and they weren’t in that environment, the founder soon realised that more often than not, their ‘mates’ weren’t giving them that case of beer or bottle of whisky they’d bet on the outcome. And getting them to honour their bets was a logistical nightmare....
				<br/>
				So they created Mate V Mate. A social betting platform which allows mates, family or complete strangers to place a wager, like a case of beer, on a sporting game or event, and know that the bet will be honoured. It’s a simple, fast and effective way to challenge your mates to back their opinions by placing a wager on what they think they know. If you need to honour a bet, everything is done for you so there is no hassle to you, except for having to listen to your mate brag about their win! And we have an honour system in place to stop them dogging you! </p>
			</div>
			<div class="col-lg-3">
				<h1>Keep in touch.</h1>
				<div class="social_outer">
					<a href="#"><i class="fa fa-facebook"></i></a>
					<a href="#"><i class="fa fa-twitter"></i></a>
					<a href="#"><i class="fa fa-google-plus"></i></a>
					<a href="#"><i class="fa fa-linkedin"></i></a>
				</div>
				<div class="address">
					<i class="fa fa-map-marker"></i>
					100 Sydney St, SYDNEY, NSW 2000
				</div>
				<div class="address">
					<i class="fa fa-mobile-phone"></i>
					+44 (0) 800 765 4321				
				</div>
				<div class="address">
					<i class="fa fa-envelope-o"></i>
					info@MateVMate.com
				</div>
			</div>
			<div class="col-lg-4">
				<h1>Contact Us</h1>
				<div class="contact_ourter">
					@if(Session::has('security_error'))
						<div class="security_code">{{ Session::get('security_error') }}</div>
						{{ Session::forget('security_error') }}
					@endif
					@if(Session::has('contact_success'))
						<div class="success_code">{{ Session::get('contact_success') }}</div>
						{{ Session::forget('contact_success') }}
					@endif
					<form id ="contact_form" method="POST" action="{{ url('contact_us') }}">
					<input type="text" placeholder="Your Name" name="yourName" class="validate[required]" data-errormessage-value-missing="Name is required" data-prompt-position="topRight:-60" value="{{ old('yourName') }}"/>
					<input type="text" placeholder="Email Address" name="emailAddress" class="validate[required, custom[email]]" data-errormessage-value-missing="Email is required" data-prompt-position="topRight:-60" value="{{ old('email') }}"/>
					<textarea style="resize: none; padding-top:10px; padding-bottom:10px;" placeholder="Your Query" name="user_query" class="validate[required]" data-errormessage-value-missing="Query is required" data-prompt-position="topRight:-60"></textarea>
					 
					{{ csrf_field() }}
					<button type="submit">SEND</button>
					</form>
				</div>
			</div>
		</div>
	</div>
    </footer>

@extends('sections.footerSection')
@endsection

@section('script')
<script>
 $(document).ready(function(){
  
  
  //~ function showNoty(type, text)
        //~ {
			//~ 
            //~ var n = noty({
                //~ text        : text,
                //~ type        : type,
                //~ dismissQueue: true,
                //~ layout      : 'bottomRight',
                //~ closeWith   : ['click'],
                //~ theme       : 'relax',
                //~ maxVisible  : 10,
                //~ /*animation   : {
                    //~ open  : 'animated shake',
                    //~ close : 'animated flipOutX',
                    //~ easing: 'swing',
                    //~ speed : 500
                //~ },*/
                //~ timeout: 1500
            //~ });
            //~ console.log('html: ' + n.options.id);
        //~ } 
        //~ var success="{{session('errors')}}";
		//~ if(success!='')
		//~ {
			//~ showNoty('success',success);
		//~ } 
		//~ var fail="{{session('error')}}";
		//~ if(fail!='')
		//~ {
			//~ showNoty('error',fail);
		//~ }
	
  //console.log(document.getElementById("CaptchaCode").Captcha);
  //~ $("#contact_form").validate({
	//~       rules:{
			//~ yourName:{ required:true },
			//~ emailAddress:{ required:true },
			//~ user_query:{ required:true },
			//CaptchaCode:{ required:true },
	//~       }, highlight: function(input) {
            //~ $(input).addClass('error');
        //~ },
    //~ errorPlacement: function(error, element){}
	    //});
});
</script>
<script type="text/javascript"> 
	$(document).ready(function(){
		formId = '#contact_form'; 
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
	});
	
</script>
@endsection
