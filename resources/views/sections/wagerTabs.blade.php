
<div id="msg_menu_div navbar-toggle collapsed" class="msg_menu" data-target="#nav-collapse1" data-toggle="collapse" style="cursor:pointer">
	<span id="msg_menu_span">My Wagers</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse1" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>
<ul class="tabs msg_row leftnav collapse" id="nav-collapse1">
	<li class="wager_tabs @if( Request::is('wagers') ) sel_tab @endif"><a href="{{ url('wagers') }}">My Wagers</a></li>
	<li class="wager_tabs @if( Request::is('wagers/open-public-wagers') ) sel_tab @endif"><a href="{{ url('wagers/open-public-wagers') }}">Open Public Wagers</a></li>
</ul>
