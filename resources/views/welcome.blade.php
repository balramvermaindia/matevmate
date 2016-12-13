@extends('layouts.app')
@extends('sections.headerSection')

@section('content')
<!-- Header -->
<header>
	<div class="container">
		<div class="row">
			<div class="col-md-6 banner_txt">
				<h1>The World's greatest<br/>
				Social Betting Platform</h1>
				<h3>Set your stakes and take on your mates</h3>
				<p>Bet with your mates on the results of your favourite sports. Wager with beverages and secure those all-important bragging rights.</p>
				<a href="#" class="banner_btn"><i class="fa fa-play"></i>Watch our video</a>
			</div>
			<div class="col-md-6">
				<div class="mobile_slide">
					<div class="siler_sports">
						<ul class="bxslider">
							<li><img src="{{ URL::to('assets/img/8.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/7.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/9.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/4.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/10.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/2.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/5.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/slide1.png') }}"/></li>
							<li><img src="{{ URL::to('assets/img/3.png') }}"/></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="sports_icons">
				<div class="container">
				<ul id="bx-pager">
					<li class="pager_holder"><a data-slide-index="0" href=""><img src="{{ URL::to('assets/img/icon1.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="1" href=""><img src="{{ URL::to('assets/img/icon2.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="2" href=""><img src="{{ URL::to('assets/img/icon3.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="3" href=""><img src="{{ URL::to('assets/img/icon4.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="4" href=""><img src="{{ URL::to('assets/img/icon5.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="5" href=""><img src="{{ URL::to('assets/img/icon6.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="6" href=""><img src="{{ URL::to('assets/img/icon7.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="7" href=""><img src="{{ URL::to('assets/img/icon8.png') }}"/></a></li>
					<li class="pager_holder"><a data-slide-index="8" href=""><img src="{{ URL::to('assets/img/icon9.png') }}"/></a></li>
				</ul>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- Header -->

<!-- Simple -->
<section id="simple">
	<div class="container">
		<div class="title">
			<h1>It's Simple</h1>
			<p>Six easy steps:</p>
		</div>
		
		<img class="stepps1" src="{{ URL::to('assets/img/steps.png') }}"/>
		
		<div class="row how_work home_steps">
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i1.png') }}"/>
				<h3><span style="color:#238FFC;">Create</span> your<br/>profile</h3>
			</div>
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i2.png') }}"/>
				<h3><span style="color:#238FFC;">Invite</span> your<br>mates to join</h3>
			</div>
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i3.png') }}"/>
				<h3><span style="color:#238FFC;">Select</span> your<br>sporting event</h3>
			</div>
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i4.png') }}"/>
				<h3><span style="color:#238FFC;">Pick</span> your wager<br>from the shop</h3>
			</div>
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i5.png') }}"/>
				<h3><span style="color:#238FFC;">Challenge</span><br>your mate</h3>
			</div> 
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i6.png') }}"/>
				<h3><span style="color:#238FFC;">Honour</span> your<br>wager via the<br>simple checkout</h3>
			</div>
			<div class="col-lg-2 col-md-4 col-sm-6">
				<img src="{{ URL::to('assets/img/i7.png') }}"/>
				<h3><span style="color:#238FFC;">Relax,</span> sit back<br>and wait for your<br>prize to arrive</h3>
			</div>
		</div>
		<p class="six_steps_bottom">If the match results in a draw, you don’t need to do a thing, your raffle tickets will automatically be assigned to you.</p>
	</div>
</section>


<section id="invite">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-6 left_mates">
				<img src="{{ URL::to('assets/img/invite.png') }}"/>
				<span>Invite your mates. Select your sport. Place a Wager.</span>
			</div>
			<div class="col-lg-6 right_mates">
				<h1>Invite your mates and start the challenge.</h1>
				<span></span>
				<h3>Select Your Sports Event and Pick your Wager</h3>
				<p>It couldn’t be simpler. Click on the sports icon to see the full range of sports ready for to you wager on. Select the sport you are interested in and then choose the event within that sport. <br>
					If you don’t have a specific event in mind, take inspiration from comments being made on the <a href="#banter-board" class="white_link">Banter Board</a>, Or take a look at the <a href="{{ url('dashboard') }}" class="white_link">Dashboard</a> to see upcoming events you may wish to make a bet on. <br>
					Once you have selected the sporting event you are interested in, you will be directed to the <a href="{{ url('shop') }}" class="white_link">Shop</a> where you can choose the beverage you would like to wager with. 
					Then all that’s left to do, is send out the challenge to your mate.</p>
				<a href="{{ url('how-it-works')}}">Read More</a>
<!--
				<div class="dot_outer">
					<a href="#"><img src="{{ URL::to('assets/img/dot1.png') }}"/></a>
					<a href="#"><img src="{{ URL::to('assets/img/dot2.png') }}"/></a>
					<a href="#"><img src="{{ URL::to('assets/img/dot2.png') }}"/></a>
					<a href="#"><img src="{{ URL::to('assets/img/dot2.png') }}"/></a>
				</div>
-->
			</div>
		</div>
	</div>
</section>

<!-- Contact Section -->
<section id="shop">
	<div class="container">
		<div class="title">
			<h1>Shop</h1>
			<p>What’s your drink of choice?</p>
		</div>

		<div class="row" style="margin-bottom:10px;">
			<div class="col-lg-6">
				<div class="shop_instruction">
					<p style="width:58px;"><span>&raquo;</span></p>
					<p>View the possibilities for your wager from a wide range and selection of beverages.</p>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="shop_instruction">
					<p style="width:58px;"><span>&raquo;</span></p>
					<p>Select your beverage and set the challenge.</p>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="shop_instruction">
					<p style="width:58px;"><span>&raquo;</span></p>
					<p style="line-height:18px; margin-top:2px;">Winning wagers have a choice of convenient delivery or pickup options across Australia.</p>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="shop_instruction">
					<p style="width:58px;"><span>&raquo;</span></p>
					<p style="line-height:18px; margin-top:2px;">To honour your losing wager, simply complete your transaction in the Shop, we’ll do the rest.</p>
				</div>
			</div>
		</div>
		<div class="row" style="position:relative">
			<span style="position:absolute; top:40%" id="sliderprev"><img src="{{ URL::to('assets/img/arrow_left.png') }}"/></span>
			<ul class="beer beer-bxslider">
				<li>
				<a href="#"><img src="{{ URL::to('assets/img/s1.png') }}"/></a>
					<div class="drink_detail">
						<h1>Carlton Cold</h1>
						<span></span>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium...</p>
						<a href="#">Buy Now</a>
					</div>
				</li>
				<li>
				<a href="#"><img src="{{ URL::to('assets/img/s2.png') }}"/></a>
					<div class="drink_detail">
						<h1>Carlton Cold</h1>
						<span></span>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium...</p>
						<a href="#">Buy Now</a>
					</div>
				</li>
				<li>
				<a href="#"><img src="{{ URL::to('assets/img/s3.png') }}"/></a>
					<div class="drink_detail">
						<h1>Carlton Cold</h1>
						<span></span>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium...</p>
						<a href="#">Buy Now</a>
					</div>
				</li>
				<li>
				<a href="#"><img src="{{ URL::to('assets/img/s4.png') }}"/></a>
					<div class="drink_detail">
						<h1>Carlton Cold</h1>
						<span></span>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium...</p>
						<a href="#">Buy Now</a>
					</div>
				</li>
				<li>
				<a href="#"><img src="{{ URL::to('assets/img/s4.png') }}"/></a>
					<div class="drink_detail">
						<h1>Carlton Cold</h1>
						<span></span>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium...</p>
						<a href="#">Buy Now</a>
					</div>
				</li>
				<li>
				<a href="#"><img src="{{ URL::to('assets/img/s4.png') }}"/></a>
					<div class="drink_detail">
						<h1>Carlton Cold</h1>
						<span></span>
						<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium...</p>
						<a href="#">Buy Now</a>
					</div>
				</li>
			</ul>
			<span style="position:absolute; top:47px; right:0px;" id="slidernext"><img src="{{ URL::to('assets/img/arrow_right.png') }}"/></span>
		</div>
	</div>
</section>


<section id="chat">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-6 right_mates">
				<h1>Let The Banter Begin...</h1>
				<span></span>
				<p>The Mate V Mate <a href="#" class="white_link">Banter Board</a>, found on the <a href="{{ url('dashboard') }}" class="white_link">dashboard</a>, allows you to interact, chat and share your opinions on different sporting events with you mates and other members. See what other people are predicting for the game/event, or challenge people with differing opinions to you, to put a wager on it!</p>
				<a href="{{ url('how-it-works')}}">Read More</a>
				</div>

			 <div class="col-lg-6 left_mates chat_bg">
				<img src="{{ URL::to('assets/img/cmts.png') }}" style="margin-top:42px;"/>
			</div>
		</div>
	</div>
</section>


<section id="honour">
	<div class="container">
		<div class="title">
			<h1>Honour System</h1>
			<p>How we keep our members honest</p>
		</div>
		<p class="text">
		Every member has a star-rating system as part of their profile, which indicates to potential challengers whether or not this person is honourable and follows through on the wagers they have previously placed. The more stars the better! Be wary of members with few or no stars!!<br/>
            At the conclusion of a match/event which a member had a wager on, both parties involved receive a notification informing them of the result of that match/event. The notification will also inform them of the next step for them to take, depending on whether they won or not. The losing member must honour the wager by completing their transaction in the <a href="{{ url('shop') }}">Shop</a> in order for the victor to receive their winnings. The winner just needs to sit back, relax and wait for their prize to arrive.
		</p>
		<div class="row">
			<div class="col-md-6" style="border-right:1px solid #ddd;">
				<h1>If the losing party does not honour their wager within __ days, they will receive no stars or have stars deducted from existing rating which appears with their profile.</h1>
				<center><img src="{{ URL::to('assets/img/arrow_down.png') }}" style="margin-top: 26px;"/></center>
				<center><img src="{{ URL::to('assets/img/rating1.png') }}" style="margin-top:10px;"/></center>
			</div>
			<div class="col-md-6">
				<h1>Once the prize is received by the winner, the losing party who honoured their wager, receives stars against their profile which indicates they are an honourable member who delivers on their wagers.</h1>
				<center><img src="{{ URL::to('assets/img/arrow_down.png') }}"/></center>
				<center><img src="{{ URL::to('assets/img/rating2.png') }}" style="margin-top:10px;"/></center>
			</div>
		</div>
		<p class="text" style="margin-top:30px;">To ensure our members can confidently make wagers that will be honoured, the following procedure is in place: Any person who does not honour an agreed wager within the required timeframe will be unable to collect any winnings until all outstanding wagers are honoured.</p>
	</div>
</section>

<section id="win_lose">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<img src="{{ URL::to('assets/img/meat.png') }}"/>
			</div>
			<div class="col-md-8 right_mates" style="padding:0px; height:auto;">
				<h1>Win or Lose</h1>
				<span></span>
				<p>Every member who places a wager on an event, automatically gets entered into the draw for the next <a href="{{ url('meat-tray-raffle') }}"class="white_link">Meat Tray Raffle</a> regardless of whether they win, lose or draw.<br/>
                Find out about past and upcoming draws on the Meat Tray Raffle page. Your past and current raffle ticket numbers can also be found there.

			</div>
		</div>
	</div>
</section>

<!-- Footer -->
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
	 
  $('.bxslider').bxSlider({
	  controls: false,
	  speed: 1000,
	  easing: 'swing',
	  pager: true,
	  pagerCustom: '#bx-pager',
	  auto: true,
	  autoStart: true,
	  autoHover: false,
	  autoDelay: 0,
	  autoDirection: 'next',
	  onSliderLoad: function() {
		$('ul#bx-pager').find('li').first().addClass('sel_slide');
	  },
	  onSlideAfter: function() {
		  $('ul#bx-pager').find('li').removeClass('sel_slide');
		  $('ul#bx-pager').find('a.active').parent('li').addClass('sel_slide');
	  }
  });
  
  console.log($('ul#bx-pager').find('a.active').parent('li').attr('class'));
  
 var slider = $('.beer-bxslider').bxSlider({
	  minSlides: 4,
	  maxSlides: 4,
	  slideWidth: 270,
	  slideHeight: 316,
	  slideMargin: 5,
	  pager: false,
	  controls: false,
	});
	$('#slidernext').click(function(){
		
      slider.goToNextSlide();
      return false;
    });

    $('#sliderprev').click(function(){
      slider.goToPrevSlide();
      return false;
    });
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

