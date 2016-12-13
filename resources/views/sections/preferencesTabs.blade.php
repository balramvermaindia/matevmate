<div id="msg_menu_div navbar-toggle collapsed" class="msg_menu submsg_menu" style="cursor:pointer" data-target="#nav-collapse2" data-toggle="collapse">
	<span id="preference_menu_span">Favorite Teams</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse2" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>
<ul class="sub_tabs msg_row leftnav collapse" id="nav-collapse2">
	<li class="preference_list @if( Request::is('profile/preferences/teams') ) sel-sub-tab @endif"><a href="{{ url('profile/preferences/teams') }}">Favorite Teams</a></li>
	<li class="preference_list @if( Request::is('profile/preferences/sports') ) sel-sub-tab @endif"><a href="{{ url('profile/preferences/sports') }}">Favorite Sports</a></li>
	<li class="preference_list @if( Request::is('profile/preferences/matches') ) sel-sub-tab @endif"><a href="{{ url('profile/preferences/matches') }}">Favorite Matches</a></li>
</ul>
