@extends('layouts.app')

@section('content')
	<a class="navbarlogo" href="{{ url('/') }}"><img src="{{ url('assets/img/logo1.png') }}"/></a>
	
	<div class="wrapper">
		<h3 class="text-center">Login</h3>
		@if (Session::has('invalid'))
			<span class="help-block">
				<strong>{{ Session::get('invalid') }}</strong>
				{{ Session::forget('invalid') }}
			</span>
		@endif
		@if (Session::has('logout'))
			<span class="help-block" style="color:green !important;">
				<strong>{{ Session::get('logout') }}</strong>
				{{ Session::forget('logout') }}
			</span>
		@endif
		<form class="form-horizontal" method="post" action="{{ url('mvm') }}">
			{{ csrf_field() }}
		  <div class="form-group">
			<label  class="col-sm-2 control-label">Username</label>
			<div class="col-sm-10">
			  <input type="text" name="username" class="form-control" placeholder="Username">
			   @if ($errors->has('username'))
					<span class="help-block">
						<strong>{{ $errors->first('username') }}</strong>
					</span>
			   @endif
			   
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 ">Password</label>
			<div class="col-sm-10">
			  <input type="password" name="password" class="form-control" placeholder="Password">
			  @if ($errors->has('password'))
				<span class="help-block">
					<strong>{{ $errors->first('password') }}</strong>
				</span>
             @endif
			</div>
		  </div>
<!--
		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <div class="checkbox">
				<label>
				  <input type="checkbox"> Remember me
				</label>
			  </div>
			</div>
		  </div>
-->
		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-default">Login</button>
			</div>
		  </div>
		</form>
	</div>
	
<style>

body{
	background:#ccc;
}
.wrapper{
	background: #fefefe none repeat scroll 0 0;
	border-radius: 10px;
	display: table;
	padding: 20px 25px;
	width: 500px;
	margin: auto;
}
.admin {
	background: none !important;
}
.help-block{
	color:red;
}

@media screen and (max-width:500px){
	.wrapper{
		width:95%;
		position:inherit;
		margin:20px auto;
	}
}
</style>
@endsection

