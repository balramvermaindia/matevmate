<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Redirect;
use App\User;
use App\Challenges;
use App\HonourTransactions;
use App\Voucher;
use App\Products;

class PaypalPaymentController extends Controller
{
	//https://github.com/amostajo/laravel-shop-gateway-omnipay
	public function viewhonor($challange_id)
    {
		$challenges = Challenges::with('event', 'mate', 'product', 'user')->Where('id', $challange_id)->first();
		$wager 		= Products::where('id',$challenges->product_id)->first();
		return view('users.wager.honor',compact('challenges', 'wager'));
	}
	
	public function doHonour( Request $request )
	{
		$rules = [ 
			'address' 								=> 'required',
			'city' 									=> 'required',
			'state' 								=> 'required',
			'zip' 									=> 'required',
			'country' 								=> 'required',
			'card_type' 							=> 'required',
			'cardholder_name' 						=> 'required',
			'cc_number' 							=> 'required',
			'expiration_month' 						=> 'required',
			'expiration_year' 						=> 'required',
			'cvv_number' 							=> 'required'
			];
			
		$this->validate($request,$rules);
		
		$payment_details 							= array();
		$payment_details 							= $request->all();
		
		if( empty($payment_details) ) {
			return back()->with('error','Some error occur while processing the transaction. Please try after some time');
		}
		
		$challengeID                        		= $payment_details['id'];
		$challenge									= Challenges::where('id', $challengeID )->first();
		$amount										= $challenge->wager_amount;
		$amount_arr									= explode(" ",$amount);
		$wager_amount								= end($amount_arr);
		$paypal_transaction_status 					= $this->doDirectPayment($payment_details,$wager_amount);
		$paypal_transaction_id						= '';
		
		if("SUCCESS" == strtoupper($paypal_transaction_status["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($paypal_transaction_status["ACK"])) {
			$paypal_transaction_id					= $paypal_transaction_status['TRANSACTIONID'];
		} else  {
			$error_msg								= urldecode($paypal_transaction_status['L_LONGMESSAGE0']);
			$request->session()->flash('paypal_error', $error_msg);
			return back()->with('error',$error_msg);
		}
		
		$honour_transaction 						= new HonourTransactions();
		$honour_transaction->challenge_id 			= $challengeID;
		$honour_transaction->user_id        		= Auth::user()->id;
		$honour_transaction->paypal_transaction_id 	= $paypal_transaction_id;
		$honour_transaction->amount                 = $wager_amount;
		$honour_transaction->currency_code			= config('constants.currency_code');
		$status										= $honour_transaction->save();
		
		if( $status ) {
			$honour_transaction_id = $honour_transaction->id;
			$max_raw_code = Voucher::max('raw_code');
			
			if( !$max_raw_code ) {
				$raw_code   = '20000001';
			} else {
				$raw_code 	= $max_raw_code + 1;
			}
			
			$voucher_code 	= 'MVM'.$raw_code;
			$voucher 								= new Voucher();
			$voucher->voucher_code					= $voucher_code;
			$voucher->raw_code						= $raw_code;
			$voucher->user_id						= $challenge->winner_user_id;
			$voucher->challenge_id					= $challengeID;
			$voucher->honour_transaction_id			= $honour_transaction_id;
			$voucher->amount						= $wager_amount;
			$voucher->currency_code					= config('constants.currency_code');
			$voucher->issue_date					= date('Y-m-d H:i:s');
			$voucher->expire_date					= date('Y-m-d H:i:s',strtotime('+90 days'));
			$save									= $voucher->save();
			
			if ( $save ) {
				$honour_status 	= 'completed';
				$update 		= Challenges::where('id', $challengeID)->update(['honour_status' => $honour_status]);
				if( $update ) {
					$userID 						= $challenge->winner_user_id;
					$this->sendNotification($userID, 'honourchallenge', $challengeID);
					$canWeSendEmail				    = $this->checkIfUserWantNotification($userID, 'WAGERHONOURED');
					$selectedTeam		 			= "";
					$wager        		 			= $challenge->wager_amount;
					$event		 		 			= $challenge->event->name;	
					if( $challenge->team_id == $challenge->event->team1_id ) {
						$selectedTeam = $challenge->event->team1;
					} else {
						$selectedTeam = $challenge->event->team2;
					}
					$winner			 				= User::where('status', 1)->where('id', $userID)->first();
					$winner_name 	 				= ucwords(@$winner->firstname . ' ' . @$winner->lastname);
					$looser							= Auth::user();
					$looser_name 					= ucwords(@$looser->firstname . ' ' . @$looser->lastname);
						
						
					if( $canWeSendEmail ) {
						
						$to      = $winner->email;
						$subject = "Honoured The Wager- MateVMate";
						$message = '<html><body>';
						$message .= "Hello " . @$winner_name . ",<br><br>";
						$message .= @$looser_name ." honoured the  wager for the challenge ".$event. " As per the wager at stake here are the MVM credit voucher details: <br><br>";
						$message .= "Voucher - " . $voucher->voucher_code . "<br>";
						$message .= "Issue Date - " . $voucher->issue_date . " to win<br>";
						$message .= "Expiry date - " . $voucher->expire_date . "<br><br><br>";
						$message .= "Please login to your MateVMate account for more details. <br><br><br><br>";
						$message .= "Thanks <br>";
						$message .= "MateVMate";
						$message .= "</body></html>";
						
						$this->sendEmail($to, $subject, $message);
					}
					
						$to      = $looser->email;
						$subject = "Honoured The Wager- MateVMate";
						$message = '<html><body>';
						$message .= "Hello " . @$looser_name . ",<br><br>";
						$message .= "you have successfully honoured the wager of worth". config('constants.currency_code') . "$wager to". @$winner_name. "<br><br>";
						$message .= "Thanks <br>";
						$message .= "MateVMate";
						$message .= "</body></html>";
					
						$this->sendEmail($to, $subject, $message);
					
					return view('users.wager.thankyou')->with('success','honoured the wager successfully.');
				}
				
			} else {
				return back()->with('error','Some error occur while processing the transaction. Please try after some time');
			}
		}
	}
	
	private function doDirectPayment($payment_details, $amount)
	{
		$address						 = isset($payment_details['address']) ? $payment_details['address'] : '';
		$city						 	 = isset($payment_details['city']) ? $payment_details['city'] : '';
		$state							 = isset($payment_details['state']) ? $payment_details['state'] : '';
		$country						 = isset($payment_details['country']) ? $payment_details['country'] : '';
		$zip						 	 = isset($payment_details['zip']) ? $payment_details['zip'] : '';
		$cardtype						 = isset($payment_details['card_type']) ? $payment_details['card_type'] : '';
		$cardholderName					 = isset($payment_details['cardholder_name']) ? $payment_details['cardholder_name'] : '';
		$cardNumber					 	 = isset($payment_details['cc_number']) ? $payment_details['cc_number'] : '';
		$expirationMonth				 = isset($payment_details['expiration_month']) ? $payment_details['expiration_month'] : '';
		$expirationYear					 = isset($payment_details['expiration_year']) ? $payment_details['expiration_year'] : '';
		$cvvNumber					 	 = isset($payment_details['cvv_number']) ? $payment_details['cvv_number'] : '';
		
		$cardholderNameArr				 = explode(" ",$cardholderName);
		$firstName						 = isset($cardholderNameArr[0]) ? $cardholderNameArr[0] : '';
		$lastName						 = isset($cardholderNameArr[1]) ? $cardholderNameArr[1] : '';
		
		
		$paymentType					 =  'Sale';
		$firstName 						 = urlencode($firstName);
		$lastName 						 = urlencode($lastName);
		$creditCardType 				 = urlencode($cardtype);
		$creditCardNumber 				 = urlencode($cardNumber);
		$expDateMonth 					 = $expirationMonth;
		$padDateMonth 					 = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT)); // Month must be padded with leading zero
		$expDateYear 					 = urlencode($expirationYear);
		$cvv2Number 					 = urlencode($cvvNumber);
		$address1 						 = urlencode($address);
		$address2 						 = urlencode('');
		$city 							 = urlencode($city);
		$state 							 = urlencode($state);
		$zip 							 = $zip;
		$country 						 = 'AU';    // US or other valid country code
		$amount 						 = $amount;  //actual amount should be substituted here
		$currencyID 					 = config('constants.currency_code');   // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		// Add request-specific fields to the request string.
		$nvpStr  						 ="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber"."&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName"."&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
		$httpParsedResponseAr 			= $this->PPHttpPost('DoDirectPayment', $nvpStr); 
		return $httpParsedResponseAr;
	}
	
	private function PPHttpPost($methodName_, $nvpStr_) 
	{
		$environment	= config('constants.paypal_environment');
		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName 	= urlencode(config('constants.paypal_API_UserName'));
		$API_Password 	= urlencode(config('constants.paypal_API_Password'));
		$API_Signature 	= urlencode(config('constants.paypal_API_Signature'));
		$API_Endpoint	= config('constants.paypal_API_Endpoint');
		if("sandbox" === $environment || "beta-sandbox" === $environment) {
			$API_Endpoint 	= "https://api-3t.$environment.paypal.com/nvp";
		}
		$version 		= urlencode('51.0');
		 
		// Set the API operation, version, and API signature in the request.
		$nvpreq 		= "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq); // Set the request as a POST FIELD for curl.
		$httpResponse = curl_exec($ch);// Get response from the server.
		if(!$httpResponse) {
			exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
		$httpResponseAr = explode("&", $httpResponse);
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}
		return $httpParsedResponseAr;
	}
	
}
