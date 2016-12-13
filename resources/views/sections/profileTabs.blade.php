<div id="msg_menu_div navbar-toggle collapsed" class="msg_menu" data-target="#nav-collapse1" data-toggle="collapse" style="cursor:pointer">
	<span id="msg_menu_span">My Profile</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse1" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>
<ul class="tabs msg_row leftnav collapse" id="nav-collapse1">
	<li class="profile_list @if( Request::is('profile') || Request::is('profile/edit') ) sel_tab @endif"><a href="{{ url('profile') }}">My Profile</a></li>
	<li class="profile_list @if( Request::is('profile/preferences') || Request::is('profile/preferences/*')) sel_tab @endif"><a href="{{ url('profile/preferences/teams') }}">Preferences </a></li>
	<li class="profile_list @if( Request::is('profile/change-password') ) sel_tab @endif"><a href="{{ url('profile/change-password') }}">Change Password</a></li>
<!--
	<li class="profile_list @if( Request::is('profile/wager-summary') ) sel_tab @endif"><a href="{{ url('profile/wager-summary') }}">Wager Summary</a></li>
-->
<!--
	<li class="profile_list @if( Request::is('profile/mates') || (Request::is('profile/pending-invitations')) || (Request::is('profile/search-mates')) || (Request::is('my-mates')) ) sel_tab @endif"><a href="{{ url('profile/mates') }}">Mates</a></li>
-->
</ul>
