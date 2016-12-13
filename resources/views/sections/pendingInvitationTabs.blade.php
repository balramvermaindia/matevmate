<div id="msg_menu_div navbar-toggle collapsed" class="msg_menu submsg_menu" style="cursor:pointer" data-target="#nav-collapse2" data-toggle="collapse" >
	<span id="preference_menu_span">Received</span>
	<a class="btn msg_menu_icon navbar-toggle collapsed" data-target="#nav-collapse2" data-toggle="collapse">
	<i class="fa fa-bars"></i>
	</a>
</div>
<ul class="sub_tabs msg_row leftnav collapse tabs" id="nav-collapse2" style="padding-top:0px;">
	<li class="preference_list @if( Request::is('mates/pending-invitations') ) sel-sub-tab @endif"><a href="{{ url('mates/pending-invitations') }}">Received</a></li>
	<li class="preference_list @if( Request::is('mates/pending-invitations/sent') ) sel-sub-tab @endif"><a href="{{ url('mates/pending-invitations/sent') }}">Sent</a></li>
</ul>
