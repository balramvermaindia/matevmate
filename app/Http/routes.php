<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group([], function () {

	Route::auth();
	
	/* requires no auth */
	Route::get('/', 'HomeController@index');
	Route::get('/how-it-works', 'HomeController@howItWorks');
	Route::get('/activate/user/{token}', 'HomeController@activateUser');
	Route::get('/users/check_email','Auth\AuthController@checkUserEmail');
	Route::post('/users/social_type','Auth\AuthController@doUpdateUserSocialType');
	Route::post('/validate/login','Auth\AuthController@validateLogin');
	Route::post('/validate/reset-password','Auth\PasswordController@validateResetPassword');
	Route::post('/reset/password', 'Auth\PasswordController@updatePassword');
	
	Route::post('contact_us','HomeController@contactUs');
	Route::get('/sign_up/thankyou','HomeController@getThankYou');
	Route::get('/reset/password/{token}', 'HomeController@resetPassword');
	Route::get('/reset-password/thankyou','HomeController@passwordThankYou');
	
	Route::get('social/login/redirect/{provider}', ['uses' => 'Auth\AuthController@redirectToProvider', 'as' => 'social.login']);
	Route::get('social/login/{provider}', 'Auth\AuthController@handleProviderCallback');
	Route::get('/term-and-conditions','HomeController@viewTermAndConditions');
	/* requires no auth */
});

Route::group(['middleware' => ['auth']], function () {
	Route::get('/dashboard','DashboardController@index');
	
	
	Route::get('/notifications/settings','NotificationController@showNotificationSettings');
	Route::post('/update/settings','NotificationController@updateNotificationSettings');
	Route::get('/notifications/sync','NotificationController@checkForNewNotification');
	Route::get('/clear-all-notifications','NotificationController@doClearAllNotty');
	Route::post('/remove-notification','NotificationController@doRemoveSelectedNotty');
	Route::get('/notifications/{page_number?}','NotificationController@showNotifications');
	Route::get('/user-profile/{id}', 'CommonController@showUserProfile');
	Route::get('/add/mate/{id}', 'CommonController@addNewMate');
	Route::get('/accept/mate/{id}', 'CommonController@acceptMateRequest');
	Route::get('/reject/mate/{id}', 'CommonController@rejectMateRequest');
	Route::get('remove/mate/{id}', 'CommonController@removeMate');
	Route::get('suggest/teams', 'CommonController@fetchUniqueTeams');
	Route::get('suggest/sports', 'CommonController@fetchUniqueSports');
	Route::get('suggest/matches', 'CommonController@fetchUniqueMatches');
	Route::post('add-favorite-team','CommonController@doAddFavoriteTeam');
	Route::post('add-favorite-sport','CommonController@doAddFavoriteSport');
	Route::post('add-favorite-match','CommonController@doAddFavoriteMatch');
	Route::get('remove/favorite/team/{id}','CommonController@doRemoveFavoriteTeam'); 
	Route::get('remove/favorite/sport/{id}','CommonController@doRemoveFavoriteSport');
	Route::get('remove/favorite/match/{id}','CommonController@doRemoveFavoriteMatch');
	
});

Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function () {
    Route::get('/', 'ProfileController@getUsersProfile');
    Route::get('/preferences/teams', 'ProfileController@getUsersPreferencesTeams');
    Route::get('/preferences/sports', 'ProfileController@getUsersPreferencesSports');
    Route::get('/preferences/matches', 'ProfileController@getUsersPreferencesMatches');
    Route::get('/change-password', 'ProfileController@changePassword');
   // Route::get('/wager-summary', 'ProfileController@getUsersBettingStatement');
    Route::get('/edit','ProfileController@editUsersProfile');
    Route::post('/edit','ProfileController@doEditUsersProfile');
    Route::get('/check_email','ProfileController@validateUserEmail');
    Route::any('/change-image','ProfileController@doUploadImage');
    Route::post('/change-password', 'ProfileController@doChangePassword');
    
});

Route::group([ 'prefix' => 'sports', 'middleware' => ['auth']], function () {
    Route::get('/', 'SportsController@getBetfairSports');
    Route::get('/{sports_sysname}','SportsController@getBetfairCompetitions');
    Route::get('/competition/{id}','SportsController@getBetfairEvents');
    Route::get('/event/{id}','SportsController@getMatchDetails');
});

Route::group([ 'prefix' => 'wagers', 'middleware' => ['auth']], function () {
    Route::get('/', 'WagerController@getUsersWagers');
    Route::get('/open-public-wagers','WagerController@getOpenPublicWagers');
	Route::get('/open-public-wagers/accept/{id}','WagerController@doAcceptOpenPublicWager');
    
});

Route::group(['prefix' => 'mates', 'middleware' => ['auth']], function () {
    Route::get('/', 'ProfileController@getUsersMates'); 
    Route::get('/pending-invitations', 'ProfileController@getPendingInvitations');
    Route::post('/search-mates', 'ProfileController@searchMates'); 
    Route::get('/pending-invitations/sent', 'ProfileController@getSentPendingInvitations');
    Route::get('/cancel/request/{id}','ProfileController@cancelMateRequest');
    Route::get('/search-mates/{page_number?}', 'ProfileController@searchMates');
});

Route::group([ 'prefix' => 'shop', 'middleware' => ['auth']], function () {
    Route::get('/', 'ShopController@getAllProducts');
    Route::get('/wager-detail/{id}','ShopController@getWagerDetails');
});

Route::group([ 'prefix' => 'meat-tray-raffle', 'middleware' => ['auth']], function () {
    Route::get('/', 'MeatTrayRaffleController@getMeatTrayRaffle');
    Route::get('past-tickets', 'MeatTrayRaffleController@getPastTickets');
}); 

//~ Route::group([ 'prefix' => 'messages', 'middleware' => ['auth']], function () {
    //~ Route::get('/', 'MessageController@viewInboxMessages');
    //~ 
//~ });


Route::group(['middleware' => ['auth']], function () {
    Route::get('/make/bet/{id}', 'ChallengeController@selectTeam');
    Route::any('/select/mate', 'ChallengeController@selectMate');
    //~ Route::post('/filter/challenge-mates', 'ChallengeController@filterChallengeMates');
    Route::post('/select/wager', 'ChallengeController@selectWager');
    Route::post('/review/wager', 'ChallengeController@reviewWager');
    Route::post('/challenge/mate', 'ChallengeController@challengeMate');
    Route::post('/assign/wager', 'ChallengeController@getMatesForWager');
    Route::post('/apply/wager', 'ChallengeController@applyWager');
    Route::get('/challenge-detail/{id}','ChallengeController@getChallengeDetail');
    Route::get('cancel-challenge/{id}','ChallengeController@doCancelChallenge');
    Route::get('cancel-honour-wager/{id}','ChallengeController@doCancelHonourWager');
   // Route::get('/my-mates', 'ProfileController@getUsersMates');
   // Route::get('/my-wagers', 'WagerController@getMyWagers');
    Route::get('/honor-wagers/{id}', 'PaypalPaymentController@viewhonor');
    Route::post('/honor-wagers/thankyou', 'PaypalPaymentController@doHonour');
    Route::get('/honor-accept/{id}', 'WagerController@accept');
    Route::get('/honor-cancel/{id}', 'WagerController@decline');
    Route::get('/banter-board/', 'BanterController@load');
	Route::post('/banter-board/', 'BanterController@load');
	Route::post('/post-to-banter', 'BanterController@postToBanter');
	Route::post('/delete/banter', 'BanterController@deleteBanterComment');
	Route::post('/update-banter-board', 'BanterController@getDeletedBanters');
	Route::post('/right-banter-board', 'BanterController@loadRightBanter');
	
	Route::get('/post-challenge-to-banter/{id}','ChallengeController@postChallengeToBanter');
	//Route::post('/search-other-mates','ChallengeController@searchOtherMates');
	Route::get('/search-other-mates','ChallengeController@searchOtherMates');
	Route::post('/select-other-mate','ChallengeController@doSelectOtherMate');
	Route::get('/getWonLossStatus/{id}','ProfileController@getWonLossStatus');
	
	// match-detail page
	Route::post('/match-detail/add-to-fav','MatchDetailController@doAddFavoriteMatch');
	Route::post('/match-detail/view-existing-wagers','MatchDetailController@viewExistingWagers');
	
});
	// mvm shop
	Route::get('/mvm','MvmShopController@viewLogin');
	Route::post('/mvm','MvmShopController@doLogin');
	Route::group([ 'prefix' => 'mvm', 'middleware' => ['mvm_auth']], function () {
		Route::post('/voucher-details','MvmShopController@voucherDetails');
		Route::post('/voucher-redeemed','MvmShopController@voucherRedeemed');
		Route::post('/cancel-voucher-redeemed','MvmShopController@cancelVoucherRedeemed');
		Route::get('/vouchers','MvmShopController@viewVouchers');
		Route::get('/history','MvmShopController@viewHistory');
		Route::get('/logout','MvmShopController@logout');
	});
	
	


