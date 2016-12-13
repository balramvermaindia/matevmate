<div id="msg_menu_div navbar-toggle collapsed" class="msg_menu" data-target="#nav-collapse1" data-toggle="collapse" style="cursor:pointer">
	<span id="msg_menu_span">My Current Tickets</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse1" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>

<ul class="tabs msg_row leftnav collapse" id="nav-collapse1">
	<li class="profile_list @if( Request::is('meat-tray-raffle') ) sel_tab @endif"><a href="{{ url('meat-tray-raffle') }} ">My Current Tickets</a></li>
	<li class="profile_list @if( Request::is('meat-tray-raffle/past-tickets') ) sel_tab @endif"><a href="{{ url('meat-tray-raffle/past-tickets') }}">My Past Tickets</a></li>
</ul>
<!--
<ul class="tabs msg_row leftnav collapse" id="nav-collapse1">
	<li class="mates_list @if( Request::is('mates') ) sel_tab @endif"><a href="{{ url('mates') }}">My Mates</a></li>
	<li class="mates_list @if( Request::is('mates/pending-invitations') || Request::is('mates/pending-invitations/*') ) sel_tab @endif"><a href="{{ url('mates/pending-invitations') }}">Pending Invitations</a></li>
	<li class="mates_list @if( Request::is('mates/search-mates') ) sel_tab @endif"><a href="{{ url('mates/search-mates') }}">Search for a Mate</a></li>
</ul>
-->
