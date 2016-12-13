<!--
<a class="navbarlogo" href="{{ url('/') }}"><img src="{{ url('assets/img/logo1.png') }}"/></a>
-->
<ul class="tab">
		<li class="active"><a href="{{ url( '/mvm/vouchers' ) }}">Vouchers</a></li>
		<li><a href="{{ url( '/mvm/history' ) }}">History</a></li>
		<li style="width:50px;border:none"><a href="{{ url( '/mvm/logout' ) }}">Logout</a></li>
	</ul>
	<div class="vouchercontent">
		<h4>Voucher Details: {{ $voucher->voucher_code }}</h4>
		<input type="hidden" name="code" value="{{ $voucher->id }}" id="code_id"/>
		<div class="form-row">
			<label>Issued To :</label>
			<div>
				<span>{{ ucfirst( $voucher->user->firstname ) }} {{ ucfirst( $voucher->user->lastname ) }}</span>
			</div>
		</div>
		<div class="form-row">
			<label>Amount :</label>
			<div>
				<span>{{  $voucher->currency_code  }} {{  $voucher->amount  }} </span>
			</div>
		</div>
		<div class="form-row">
			<label>Issued On :</label>
			<div>
				<span>{{  date('D d M h:i A', strtotime($voucher->issue_date))  }} </span>
			</div>
		</div>
		<div class="form-row">
			<label>Expired On :</label>
			<div>
				<span>{{  date('D d M h:i A', strtotime($voucher->expire_date))  }} </span>
			</div>
		</div>
		<div class="form-row">
			<label>Redeemed :</label>
			<div>
				@if( $voucher->is_redeemed == 0 )
					<span style="color:green;"> No </span>
				@else
					<span style="color:red;"> Yes </span>
				@endif	
			</div>
		</div>
		<div class="form-row">
			<label>Expired :</label>
			<div>
				@if( $voucher->is_expired == 0 )
					<span style="color:green;"> No </span>
				@else
					<span style="color:red;"> Yes </span>
				@endif	
			</div>
		</div>
		
		<div style="margin-top:10px;">
			<input class="btn btn-danger cancel" type="button" name="cancel" value="Cancel"/>
			@if( $voucher->is_redeemed == 0 &&  $voucher->is_expired == 0 )
				<input class="btn btn-success redeem pull-right" type="button" name="redeem" value="Redeem"/>
			@endif
		</div>
	</div>
<style>
.vouchercontent h4{
	background:#eee;
	padding:10px;
}
.vouchercontent .form-row{border-bottom:1px solid #ddd;}
.vouchercontent > div:nth-child(even){background:#f5f5f5;}
.navbarlogo{
	margin:10px auto;
	display:block;
	text-align:center;
}
.navbarlogo img{
	width:280px;
	margin-top:20px;
}
</style>

