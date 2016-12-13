@section('usersLeftSideNavigation')

	<div class="admin_left">
		<div class="user_pic_outer">
			<div class="user_pic">
				@if ( isset(Auth::user()->photo) && !empty(Auth::user()->photo) )
					<img src="{{ url('assets/users/img/user_profile/'.Auth::user()->photo) }}" id="feedback">
				@else
					<img src="{{ url('assets/img/user.png') }}">
				@endif
			</div>
			<p>{{@Auth::user()->firstname}} {{@Auth::user()->lastname}}</p>
		</div>
		<ul class="left_links">
			<li class="@if( Request::is('dashboard') ) sel_left @endif"><a href="{{ url('dashboard') }}"><i class="fa fa-dashboard" aria-hidden="true"></i>Dashboard</a></li>
			<li class="@if( Request::is('profile') || Request::is('profile/*') ) sel_left @endif"><a href="{{ url('profile') }}"><i class="fa fa-user" aria-hidden="true"></i>My Profile</a></li>
			<li class="@if( Request::is('sports') || Request::is('sports/*') || Request::is('make/bet/*') ) sel_left @endif"><a href="{{ url('sports')}}"><i class="fa fa-flag-checkered" aria-hidden="true"></i>All Sports</a></li>
			<li class="@if( Request::is('wagers') || Request::is('wagers/*') ) sel_left @endif"><a href="{{ url('wagers') }}"><i class="fa fa-beer" aria-hidden="true"></i>My Wagers</a></li>
			<li class="@if( Request::is('mates') || Request::is('mates/*')) sel_left @endif"><a href="{{ url('mates') }}"><i class="fa fa-group" aria-hidden="true"></i>My Mates</a></li>
			<li class="@if( Request::is('shop') || Request::is('shop/*') ) sel_left @endif"><a href="{{ url('shop')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i>Shop</a></li>
			
			<li class="@if( Request::is('banter-board') ) sel_left @endif"><a href="{{ url('banter-board') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>Banter Board</a></li>
			
			<li class="@if( Request::is('meat-tray-raffle') ) sel_left @endif"><a href="{{ url('meat-tray-raffle') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>Meat Tray Raffle</a></li>
			<li class="@if( Request::is('notifications') || Request::is('notifications/settings') ) sel_left @endif"><a href="{{ url('notifications') }}"><i class="fa fa-bell" aria-hidden="true"></i>Notifications</a></li>
		</ul>
	</div>

@endsection
