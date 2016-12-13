
<!--
<ul class="sub_tabs">
	<li class="@if( Request::is('profile/mates') || Request::is('my-mates') ) sel-sub-tab @endif"><a href="{{ url('profile/mates') }}">My Mates</a></li>
	<li class="@if( Request::is('profile/pending-invitations') ) sel-sub-tab @endif"><a href="{{ url('profile/pending-invitations') }}">Pending Invitations</a></li>
	<li class="@if( Request::is('profile/search-mates') ) sel-sub-tab @endif"><a href="{{ url('profile/search-mates') }}">Search for a Mate</a></li>
</ul>
-->

<div id="msg_menu_div navbar-toggle collapsed" class="msg_menu" data-target="#nav-collapse1" data-toggle="collapse" style="cursor:pointer">
	<span id="msg_menu_span">My Mates</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse1" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>
<ul class="tabs msg_row leftnav collapse" id="nav-collapse1">
	<li class="mates_list @if( Request::is('mates') ) sel_tab @endif"><a href="{{ url('mates') }}">My Mates</a></li>
	<li class="mates_list @if( Request::is('mates/pending-invitations') || Request::is('mates/pending-invitations/*') ) sel_tab @endif"><a href="{{ url('mates/pending-invitations') }}">Pending Invitations</a></li>
	<li class="mates_list @if( Request::is('mates/search-mates') ) sel_tab @endif"><a href="{{ url('mates/search-mates') }}">Search for a Mate</a></li>
</ul>
