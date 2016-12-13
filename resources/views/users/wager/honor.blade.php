@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')

	<div class="mid_section no_bg_hover">
		<div class="list_outer outcome_outer detail_outer">
			<h1><span>Honour Wager</span> <a href="{{ url('challenge-detail/'.$challenges->id) }}" class="pull-right btn accpet_btn" style="font-family:ubuntumedium;color:#fff;margin:5px;padding:6px 15px;">Back</a></h1>

		<div>
			<div class="no_bg_hover honour_wager" style="padding:0px 20px;">
				<div class="profile_title " style="margin-top:10px;">
					@if(Session::has('paypal_error'))
							<div class="alert alert-danger alert-dismissible" role="alert">
								{{ Session::get('paypal_error') }}

<!--
								<i class="fa fa-close pull-right" aria-hidden="true"></i>
-->

							</div>
						@endif
					<span>Challenge Details</span>
				</div>
				<?php $img_url = getSportsImageBySportsID( @$challenge->event->betfair_sports_id ); ?>
				<div class="list-row shipping_outer" style="border-bottom:1px solid #f5f5f5;padding:0px">
					
					<div class="form-row" style="display:inline-block;width:auto;vertical-align:middle;">
						<label style="width:140px;text-align:left;padding-right:5px;">Match:</label>
					</div>
					<div class="info" style="display: inline-block; padding-top: 8px; margin-bottom: 5px; vertical-align: top;">
						<span class="sport_image"><img src="{{ url($img_url) }}"/>{{ $challenges->event->name }}</span>
						<p>
							<i aria-hidden="true" class="fa fa-calendar"></i>
							{{ showDateTime(@$challenges->event->startdatetime, @$challenges->event->timezone, "D d M h:i A") }}
						</p>
					</div>
				</div>
				<div class="list-row shipping_outer" style="border-bottom:1px solid #f5f5f5;padding:0px">
					<div class="form-row" style="display:inline-block;width:auto;vertical-align:middle;">
						<label style="width:140px;text-align:left;padding-right:5px;">Wager made with:</label>
					</div>
					<div class="info" style="display: inline-block; padding-top: 8px; margin-bottom: 5px; vertical-align: top;">
						<span>{{ ucwords(@$challenges->mate->firstname .' '.@$challenges->mate->lastname) }}</span>
					</div>
				</div>
				<div class="list-row shipping_outer" style="border-bottom:1px solid #f5f5f5;padding:0px">
					<div class="form-row" style="display:inline-block;width:auto;vertical-align:middle;">
						<label style="width:140px;text-align:left;padding-right:5px;">Wager at stake:</label>
					</div>
					<div class="info" style="display: inline-block; padding-top: 8px; margin-bottom: 5px; vertical-align: top;">
						<span>{{ $challenges->wager_amount }}</span>
					</div>
				</div>
				


<!--
				@if($challenges->user_id == Auth::user()->id)
					<div class="list-row shipping_outer" style="border:none;padding:0px 20px;">
						
						
						
						<div class="form-row" style="display:inline-block;width:auto;vertical-align:middle;">
						<label style="width:140px;text-align:left;padding-right:5px;">Recipient:</label>
						</div>
						<div class="info" style="display: inline-block; padding-top: 8px; margin-bottom: 5px; vertical-align: top;">
						<span>{{ ucwords(@$challenges->mate->firstname .' '.@$challenges->mate->lastname) }}</span>
						</div>
					</div>
				@else
					<div class="list-row">
						<div class="form-row" style="display:inline-block;width:auto;vertical-align:middle;">
						<label style="width:113px;text-align:left;padding-right:5px;">Recipient:</label>
						</div>
						<div class="info" style="display: inline-block; padding-top: 8px; margin-bottom: 5px; vertical-align: top;">
						<span>{{ ucwords(@$challenges->user->firstname .' '.@$challenges->user->lastname) }}</span>
						</div>
					</div>
				@endif
-->

				
					
				<div>
					
					<div class="profile_title " style="margin-top:10px;">
					<span>Billing Address</span>
				</div>
					
					<form action="{{ url('honor-wagers/thankyou') }}" method="POST" id="honor_form">
						{{ csrf_field() }}
						<input type="hidden" name="id" value="{{ $challenges->id }}"/>

<!--
						<div class="form-row">
							<label>First Name:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text"  name="first_name" class="validate[required]" data-errormessage-value-missing="First Name is required" data-prompt-position="topLeft:50" value="{{ old('first_name') }}">
								@if ($errors->has('first_name'))
									<p class="help-block">
										{{ $errors->first('first_name') }}
									</p>
								@endif
							</div>
							
						</div>

						<div class="form-row">
							<label>Last Name:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text"  name="last_name" class="validate[required]" data-errormessage-value-missing="Last Name is required" data-prompt-position="topLeft:50" value="{{ old('last_name') }}">
								@if ($errors->has('last_name'))
									<p class="help-block">
										{{ $errors->first('last_name') }}
									</p>
								@endif
							</div>
						</div>
-->
						<div class="form-row">
							<label>Address:<span style="color:red">*</span></label>
							<div class="info">
								<textarea rows="4" cols="50"  name="address" class="validate[required]" data-errormessage-value-missing="Address is required" data-prompt-position="topLeft:50" style="resize:none; height:auto;">{{ old('address') }}</textarea>
								@if ($errors->has('address'))
									<p class="help-block">
										{{ $errors->first('address') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>City:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text"  name="city" class="validate[required]" data-errormessage-value-missing="City is required" data-prompt-position="topLeft:50" value="{{ old('city') }}">
								@if ($errors->has('city'))
									<p class="help-block">
										{{ $errors->first('city') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>State:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" class="validate[required]" data-errormessage-value-missing="State is required" data-prompt-position="topLeft:50"   name="state" value="{{ old('state') }}">
								@if ($errors->has('state'))
									<p class="help-block">
										{{ $errors->first('state') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>Country:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" name="country" class="validate[required]" data-errormessage-value-missing="Country is required" data-prompt-position="topLeft:50" value="Australia">
								@if ($errors->has('country'))
									<p class="help-block">
										{{ $errors->first('country') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>Zip:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" name="zip" class="validate[required]" data-errormessage-value-missing="Zip is required" data-prompt-position="topLeft:50" value="{{ old('zip') }}">
								@if ($errors->has('zip'))
									<p class="help-block">
										{{ $errors->first('zip') }}
									</p>
								@endif
							</div>
						</div>

				<div class="profile_title" style="margin-top:10px;">
					<span>Payment Details</span>
				</div>

				<div>
					<div class="form-row">
							<label>Card Type:<span style="color:red">*</span></label>
							<div class="info">
								<select name='card_type' id="card_type" class="form-control col-md-4" class="validate[required]" data-errormessage-value-missing="Card Type is required" data-prompt-position="topLeft:50" style="width:35%; margin-right:10px; display:inline-block;">
									<option value="visa" @if( "visa" == old('card_type') ) selected=selected @endif>Visa</option>
									<option value="MasterCard" @if( "MasterCard" == old('card_type') ) selected=selected @endif>Master Card</option>
									<option value="AmericanExpress" @if( "AmericanExpress" == old('card_type') ) selected=selected @endif>American Express</option>
								</select>
								@if ($errors->has('card_type'))
									<p class="help-block">
										{{ $errors->first('card_type') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>Cardholder Name:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text"  name="cardholder_name" class="validate[required]" data-errormessage-value-missing="Cardholder Name is required" data-prompt-position="topLeft:50" value="{{ old('cardholder_name') }}">
								@if ($errors->has('cardholder_name'))
									<p class="help-block">
										{{ $errors->first('cardholder_name') }}
									</p>
								@endif
							</div>
						</div>
						
						<div class="form-row">
							<label>Card Number:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text"  name="cc_number" class="validate[required]" data-errormessage-value-missing="CC Number is required" data-prompt-position="topLeft:50" value="{{ old('cc_number') }}">
								@if ($errors->has('cc_number'))
									<p class="help-block">
										{{ $errors->first('cc_number') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>Expiry Date:<span style="color:red">*</span></label>
							<div class="info">
								<select name='expiration_month' id="expiration_month" class="form-control col-md-4 validate[required]" data-errormessage-value-missing="Month is required" data-prompt-position="topLeft:50" style="width:35%; margin-right:10px; display:inline-block;">
									<option value=''>Month</option>
									<option value='01' @if( "01" == old('expiration_month') ) selected=selected @endif>January</option>
									<option value='02' @if( "02" == old('expiration_month') ) selected=selected @endif>February</option>
									<option value='03' @if( "03" == old('expiration_month') ) selected=selected @endif>March</option>
									<option value='04' @if( "04" == old('expiration_month') ) selected=selected @endif>April</option>
									<option value='05' @if( "05" == old('expiration_month') ) selected=selected @endif>May</option>
									<option value='06' @if( "06" == old('expiration_month') ) selected=selected @endif>June</option>
									<option value='07' @if( "07" == old('expiration_month') ) selected=selected @endif>July</option>
									<option value='08' @if( "08" == old('expiration_month') ) selected=selected @endif>August</option>
									<option value='09' @if( "09" == old('expiration_month') ) selected=selected @endif>September</option>
									<option value='10' @if( "10" == old('expiration_month') ) selected=selected @endif>October</option>
									<option value='11' @if( "11" == old('expiration_month') ) selected=selected @endif>November</option>
									<option value='12' @if( "12" == old('expiration_month') ) selected=selected @endif>December</option>
								</select>
								<select name='expiration_year' id="expiration_year" class="form-control col-md-4 validate[required]" data-errormessage-value-missing="Year is required" data-prompt-position="topLeft:50" style="width:35%; display:inline-block;">
									<option value=''>Year</option>
									<?php
										$current_year= date("Y");
										for($i=1; $i <= 10; $i++){
											
									?>
										<option value="<?php echo $current_year; ?>" @if( $current_year == old('expiration_year') ) selected=selected @endif><?php echo $current_year; ?></option>
									<?php
										$current_year++;
										}
									?>
								</select>
								@if ($errors->has('expiration_month'))
									<p class="help-block">
										{{ $errors->first('expiration_month') }}
									</p>
								@endif
								 @if ($errors->has('expiration_year'))
									<p class="help-block">
										{{ $errors->first('expiration_year') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>CVV Number:<span style="color:red">*</span></label>
							<div class="info">
								<input type="text" name="cvv_number" class="validate[required]" data-errormessage-value-missing="CVV Number is required" data-prompt-position="topLeft:50" value="{{ old('cvv_number') }}">
								@if ($errors->has('cvv_number'))
									<p class="help-block">
										{{ $errors->first('cvv_number') }}
									</p>
								@endif
							</div>
						</div>
						<div class="form-row">
							<label>&nbsp;</label>
							<div class="info">
								<button type="submit" class="btn_blue" style="border:none;line-height:17px;">Submit</button>
								<a class="black_blue" href="{{ url('challenge-detail/'.$challenges->id) }}">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>		
	</div>
	</div>
	</div>
	<style>
		#honor_form .form-row label
		{
			text-align: left !important;
			width: 140px;
		}
		.help-block{
			color:red;
			font-size:13px;
			font-weight:normal;
			margin-bottom:0px;
		}
		.alert-danger {
    background-color: #f2dede;
    border-color: #ebcccc;
    color: #a94442;
    margin-right:10px;
	}
	.sport_image img {
		margin-left: -11px !important;
	}
	.list_outer div.list-row:nth-child(2n+1) {
		background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
	}
	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

@section('script')
<script type="text/javascript"> 
	$(document).ready(function(){
		formId = '#honor_form'; 
		$("input").on('click', '', function() {
			var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
			$(inputFieldFormError).fadeOut(150, function() {
				$(this).remove();
			});
		});
		
		$(formId).validationEngine('attach',{
			autoHidePrompt:true, 
			autoHideDelay: 5000,
			
		});
	});
</script>

@endsection
