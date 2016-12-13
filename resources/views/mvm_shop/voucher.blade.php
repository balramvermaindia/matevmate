@extends('layouts.app')

@section('content')
<a class="navbarlogo" href="{{ url('/') }}"><img src="{{ url('assets/img/logo1.png') }}"/></a>
<div class="wrapper">
	<ul class="tab">
		<li class="active"><a href="{{ url( '/mvm/vouchers' ) }}">Vouchers</a></li>
		<li><a href="{{ url( '/mvm/history' ) }}">History</a></li>
		<li style="width:50px;border:none"><a href="{{ url( '/mvm/logout' ) }}">Logout</a></li>
	</ul>
	<div class="vouchercontent">
		<form class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-2 ">Voucher Code</label>
				<div class="col-sm-10">
				  <input type="text" name="code" class="form-control" id="code" placeholder="Voucher Code">
				   @if ($errors->has('code'))
						<span class="help-block">
							<strong>{{ $errors->first('code') }}</strong>
						</span>
					@endif
				</div>
			  </div>
			  <div class="form-group">
				  <div class="col-sm-offset-2 col-sm-10">
					<button type="button" class="btn btn-success submit">Submit</button>
				  </div>
			</div>
		  </form>
	</div>
</div>
<style>
	body{
	background:#ccc;
}
	.wrapper{
		margin:auto;
		width:1000px;
		background:#fefefe;
		min-height:200px;
		border:1px solid #ddd;
	}
	
	@media screen and (max-width:1000px){
	.wrapper{
		width:95%;
		position:inherit;
		margin:20px auto;
	}
}
</style>
@endsection
@section('script')
	<script type="text/javascript">
	$(document).ready(function(){
		
		$(document).off('click','.submit').on('click','.submit',function(e){
			e.preventDefault();
			var code = $('#code').val();
			if( code ) {
				$.ajax({
					type:'Post',
					url : siteurl + '/mvm/voucher-details',
					data: {'code': code, '_token':"{{ csrf_token() }}" },
					success: function(response) {
						if( response.type == "success" ) {
							$('.wrapper').html(response.data);
						} else if ( response.type == "error" ) {
							generate('error', response.msg);
						}
					}
				});
				
			} else {
				generate('error','Enter the voucher code');
			}
		});
		
		$(document).off('click', '.redeem').on('click', '.redeem', function(e){
			e.preventDefault();
			var id = $('#code_id').val();
			if( id ) {
				$.ajax({
					type:'Post',
					url : siteurl + '/mvm/voucher-redeemed',
					data: { 'id': id, '_token':"{{ csrf_token() }}" },
					success: function(response) {
						if( response.type == "success" ) {
							$('.wrapper').html(response.data);
							generate('success', response.msg);
						} else if ( response.type == "error" ) {
							generate('error', response.msg);
						}
					}
				});
			} else {
				return false;
			}
		});
		
		$(document).off('click','.cancel').on('click','.cancel',function(e){
			e.preventDefault();
			window.location.href = siteurl + '/mvm/vouchers';
			
		});
		
		
	});
	</script>
@endsection

