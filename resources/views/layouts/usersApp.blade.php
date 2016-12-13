<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MateVMate</title>
    <link href="{{ url('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/freelancer.css') }}" rel="stylesheet">
	<link href="{{ url('assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ url('assets/users/css/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
	<link href="{{ url('assets/users/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ url('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('assets/css/validationEngine.jquery.css') }}" rel="stylesheet">
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
		var siteurl = "{{ url('/') }}";
	</script>
	<style>
	.formError{margin-top: -35px !important; opacity: 1 !important; text-transform: none !important}
	.custom-confirm-matevmate {
    display: table;
    width: 35%;
    margin:auto;
	}
   </style>

</head>

<body id="page-top" class="index">
	
    @yield('usersHeaderSection');

 	
 	<div class="container-fluid admin">
 		<div class="row">
 			@yield('usersLeftSideNavigation')
 			@yield('usersContent')
 			@yield('usersRightSideNavigation')
 		</div>
 	</div>

	@yield('usersFooterSection')
    <script src="{{ url('assets/js/jquery.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ url('assets/js/bootstrap.min.js') }}"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{ url('assets/js/classie.js') }}"></script>
    <script src="{{ url('assets/js/cbpAnimatedHeader.js') }}"></script>

    <!-- Contact Form JavaScript -->
    <script src="{{ url('assets/js/jqBootstrapValidation.js') }}"></script>
    <script src="{{ url('assets/js/contact_me.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ url('assets/js/freelancer.js') }}"></script>
    <script src="{{ url('assets/users/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <script src="{{ url('assets/users/js/jquery.collapse.js') }}"></script>
    <script src="{{ url('assets/users/js/jquery-ui.js') }}"></script>
    <script src="{{ URL::to('assets/js/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ URL::to('assets/js/jquery.validationEngine.js') }}"></script>
    <script src="{{ URL::to('assets/js/jquery.noty.packaged.js') }}"></script>
    <script src="{{ URL::to('assets/js/script.js') }}"></script>
    <script src="{{ URL::to('assets/js/moment.js') }}"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<script src="{{ URL::to('assets/users/js/jquery.confirm.js') }}"></script>
	<script src="{{ URL::to('assets/users/js/amcharts.js') }}"></script>
	<script src="{{ URL::to('assets/users/js/pie.js') }}"></script>
	<script src="{{ URL::to('assets/js/right_banter.js') }}"></script>
	@yield('script')
	<script>
		(function($){
			$(window).load(function(){
				$("#rightSideDiv").mCustomScrollbar(
					{
						theme:"dark-3",
						scrollButtons:{enable:true},
						scrollInertia:400
					}
				);
			});
		})(jQuery);
		
		var success = "{{Session::get('success')}}";
		if( success != '' ) {
			generate('success', success);
		}
		var fail = "{{session('error')}}";
		if( fail != '' ) {
			generate('error', fail);
		}
	</script>
	<script type="text/javascript">

		$(document).ready(function(){
			if ($('.profile_list').length) {
				var title = $('.sel_tab').children('a').html();
				$('#msg_menu_span').html('');
				$('#msg_menu_span').html(title);
			}
			
			if($('.preference_list').length){
				var title = $('.sel-sub-tab').children('a').html(); 
				$('#preference_menu_span').html('');
				$('#preference_menu_span').html(title);
			}
			if ($('.mates_list').length) {
				var title = $('.sel_tab').children('a').html();
				$('#msg_menu_span').html('');
				$('#msg_menu_span').html(title);
			}
			if ($('.wager_tabs').length) {
				var title = $('.sel_tab').children('a').html();
				$('#msg_menu_span').html('');
				$('#msg_menu_span').html(title);
			}
			
		});
			
	</script>
</body>

</html>
