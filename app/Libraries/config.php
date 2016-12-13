<?php
include_once("inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########

//LIVE
$appId 		='482497055277703'; //Facebook App ID
$appSecret  = '9c6e0d1430750312d0694b633e58a687'; // Facebook App Secret
$homeurl    = 'http://54.206.39.19/register';  //return to home

//Local
//~ $appId ='1799555813610335'; //Facebook App ID
//~ $appSecret = '4b27f14388a1258d89fc8d6402e155c8'; // Facebook App Secret
//~ $homeurl = 'http://localhost/matevmate/public/register';  //return to home


$fbPermissions = 'email,user_birthday';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getAccessToken();
$fbuser = $facebook->getUser();
?>
