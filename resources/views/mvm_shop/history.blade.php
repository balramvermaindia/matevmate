
@extends('layouts.app')

@section('content')
<a class="navbarlogo" href="{{ url('/') }}"><img src="{{ url('assets/img/logo1.png') }}"/></a>
<div class="wrapper">
	<ul class="tab">
		<li><a href="{{ url( '/mvm/vouchers' ) }}">Vouchers</a></li>
		<li class="active"><a href="{{ url( '/mvm/history' ) }}">History</a></li>
		<li style="width:50px;border:none"><a href="{{ url( '/mvm/logout' ) }}">Logout</a></li>
	</ul>
	<div class="vouchercontent">
		<table class="table table-striped">
			<tr>
				<td><b>Voucher Code</b></td>
				<td><b>Amount</b></td>
				<td><b>Redemption Date</b></td>
			</tr>
			@if(count($vouchers))
				@foreach( $vouchers as $voucher)
					<tr>
						<td>{{ @$voucher->voucher_code }}</td>
						<td>{{ @$voucher->currency_code }} {{ @$voucher->amount }}</td>
						<td>{{ date('D d M h:i A', strtotime(@$voucher->redemption_date) ) }}</td>
					</tr>
				@endforeach
			@else
			<tr>
				<td colspan="3">No Recoed Found.</td>
			@endif
		</table>
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


