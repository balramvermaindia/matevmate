@section('usersHeaderSection')
<nav class="navbar navbar-default navbar-fixed-top inner_header">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
				<div class="icon_outer noti">
					<a title="Notifications" href="{{ url('/notifications') }}"><i aria-hidden="true" class="fa fa-bell"></i><div id="mateVmateJewelCell" class="ntifi_msg"></div></a>
				</div>
                <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('assets/img/logo1.png') }}"/></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            	<div class="icon_outer">
                <a href="{{ url('/notifications') }}" title="Notifications"><i class="fa fa-bell" aria-hidden="true"></i><div class="ntifi_msg" id="mateVmateJewel"></div></a>
                <a href="#" title="Messages"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                <a class="power" href="{{ url('/logout') }}" title="Logout"><i class="fa fa-power-off" aria-hidden="true"></i></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                	<li class="hidden1 @if( Request::is('dashboard') ) current @endif">
                        <a href="{{ url('/dashboard')}}"><i class="fa fa-dashboard" aria-hidden="true"></i>Dashboard</a>
                    </li>
                    <li class="hidden1 @if( Request::is('profile') || Request::is('profile/*') ) current @endif">
                        <a href="{{ url('/profile') }}"><i class="fa fa-user" aria-hidden="true"></i>My Profile</a>
                    </li>
                    <li class="hidden1 @if( Request::is('sports') || Request::is('sports/*') || Request::is('make/bet/*') ) current @endif">
                        <a href="{{ url('sports')}}"><i class="fa fa-flag-checkered" aria-hidden="true"></i>All Sports</a>
                    </li>
                    <li class="hidden1 @if( Request::is('wagers') || Request::is('wagers/*') ) current @endif">
                        <a href="{{ url('wagers') }}"><i class="fa fa-beer" aria-hidden="true"></i>My Wagers</a>
                    </li>
                    <li class="hidden1 @if( Request::is('mates') || Request::is('mates/*')) current @endif">
                        <a href="{{ url('mates') }}"><i class="fa fa-group" aria-hidden="true"></i>My Mates</a>
                    </li>
                    <li class="hidden1 @if( Request::is('shop') || Request::is('shop/*') ) current @endif">
                        <a href="{{ url('shop')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i>Shop</a>
                    </li>
                    <li class="hidden1 @if( Request::is('banter-board') ) current @endif">
                        <a href="{{ url('banter-board')}}"><i class="fa fa-list-alt" aria-hidden="true"></i>Banter Board</a>
                    </li>
                    <li class="hidden1 @if( Request::is('meat-tray-raffle') ) current @endif">
                        <a href="{{ url('meat-tray-raffle') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>Meat Tray Raffle</a>
                    </li>
<!--
                    <li class="hidden1 @if( Request::is('notifications') ) current @endif">
                        <a href="{{ url('/notifications') }}">Notifications</a>
                        <div class="ntifi_msg mbile_notifi" id="mateVmateJewelCell">20</div>
                    </li>
-->
                    <li class="btn mob-hide">
                    	<a href="{{ url('/sports') }}">Challenge A Mate</a>
                    </li>
                    <li class="btn mob-hide">
                    	<a href="{{ url('shop') }}">Take me to the Shop</a>
                    </li>
                    <li class="btn hidden1">
                    	<a href="{{ url('/logout') }}"><i class="fa fa-power-off" aria-hidden="true"></i>Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
@endsection
