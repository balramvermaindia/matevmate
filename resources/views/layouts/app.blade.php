<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mate V Mate</title>
    <link href="{{ URL::to('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/freelancer.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/validationEngine.jquery.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('assets/slider/jquery.bxslider.css') }}" rel="stylesheet" />

	<script>
		var siteurl = "{{ url('/') }}";
	</script>
	<style>
   .formError{margin-top: -35px !important; opacity: 1 !important; text-transform: none !important}
   </style> 
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="page-top" class="index">
	
	@yield('headerSection')
	
    @yield('content')
    
    @yield('footerSection')
	
	<!-- jQuery -->
    <script src="{{ URL::to('assets/js/jquery.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{ URL::to('assets/js/classie.js') }}"></script>
    <script src="{{ URL::to('assets/js/cbpAnimatedHeader.js') }}"></script>

    <!-- Contact Form JavaScript -->
    <script src="{{ URL::to('assets/js/jqBootstrapValidation.js') }}"></script>
    <script src="{{ URL::to('assets/js/contact_me.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ URL::to('assets/js/freelancer.js') }}"></script>
    
    <script src="{{ URL::to('assets/slider/jquery.bxslider.js') }}"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
    <script src="{{ URL::to('assets/js/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ URL::to('assets/js/jquery.validationEngine.js') }}"></script>
    <script src="{{ URL::to('assets/js/jquery.noty.packaged.js') }}"></script>
    <script src="{{ URL::to('assets/js/display_notty_msg.js') }}"></script>
   
    
    @yield('script')
    
    @yield('loginscripts')
    
</body>
</html>
