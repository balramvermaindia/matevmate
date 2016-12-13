<div id="msg_menu_div" class="msg_menu">
	<span id="msg_menu_span">Inbox</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse1" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>
<ul class="tabs msg_row leftnav collapse" id="nav-collapse1">
	<li class="message_list @if( Request::is('profile') || Request::is('profile/edit') ) sel_tab @endif"><a href="#">My Profile</a></li>
	<li class="message_list"><a href="{{ url('profile/preferences/teams') }}">Preferences </a></li>
	<li class="message_list"><a href="{{ url('profile/change-password') }}">Change Password</a></li>
	<li class="message_list"><a href="{{ url('profile/wager-summary') }}">Wager Summary</a></li>
</ul>
