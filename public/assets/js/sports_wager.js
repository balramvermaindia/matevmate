var token	  		= $("#sports_token").val();
postToPSBanter 		= false;

$(document).ready(function() {
	var $elem 			= $('.pref-main-chat-matevmate');
	$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
	
	$(document).off('click touchstart', '.delete_pref_comment').on('click touchstart', '.delete_pref_comment', function(e) {
		var banterID 	= $(this).parents('div.person').attr('data-pef-banter-id');
		var elem		= $(this).parents('div.person');
		if ( banterID ) {
			deleteAjaxFunction(banterID, '/delete/banter', token, elem, 'Are you sure you want to delete this comment?', 'Confirmation Required' );
		} else {
			generate('error', 'Unable to delete this comment right now. Please try again in some time.');
		}
	});
});

// auto delete banter //
(function checkDeletedMessage() {
	var data		= {};
	var filterType 	= $('.pref_chat_selector_type_holder').attr('data-ps-filter-type');
	var firstID		= $('.pref-only-bentor').first().attr('data-pef-banter-id');
	var lastID		= $('.pref-only-bentor').last().attr('data-pef-banter-id');
	
	if ( filterType == 'game' ) {
		var eventID 	= $('#banter_type_pref_first_event').attr('data-event-type-pref-event-id');
		
		if ( lastID ) {
			data 	= {"_token" : token, 'event_id' : eventID, "first_id" : firstID, "last_id" : lastID };
		}
	}
	
	$.ajax({
		url		: siteurl + "/update-banter-board",
		type	: 'POST',
		data	: data,
		dataType: 'JSON',
		async 	: true,
		success: function(response) {
			if (response != 'false') {
				if ( lastID ) {
					response = JSON.stringify(response);
					response = $.parseJSON(response);
					for (var i in response) {
						 $('#mvmso_' + response[i]).remove();
					}
				}
			}
		},
		complete: function() {
			setTimeout(checkDeletedMessage, 4000);
		}
	});
	
})();
// auto delete banter //

// auto sync preferred sports/team banter board //			
(function syncWager() {
	var data		= {};
	var filterType 	= $('.pref_chat_selector_type_holder').attr('data-ps-filter-type');
	var lastID		= $('.pref-only-bentor').last().attr('data-pef-banter-id');
	
	if ( filterType == 'game' ) {
		var eventID 	= $('#banter_type_pref_first_event').attr('data-event-type-pref-event-id');
		
		if ( lastID ) {
			data 	= {"_token" : token, 'pref_event_id' : eventID, "check_last_pref_wager_only" : "true", "last_id" : lastID, "sync_msg_ps" : "true", "ps_wager_filter_type" : "game", "banter": "right"};
		} else {
			data 	= {"_token" : token, 'pref_event_id' : eventID, "check_last_pref_wager_only" : "false", "sync_msg_ps" : "true", "ps_wager_filter_type" : "game", "banter": "right"};
		}
	}
	
	//if ( postToPSBanter == false ) {
		$.ajax({
			url		: siteurl + "/banter-board",
			type	: 'POST',
			data	: data,
			dataType: 'text',
			async 	: true,
			success: function(response) {
				if (response != 'false') {
					if ( lastID ) {
						$('.pref-main-chat-matevmate').append(response);
					} else {
						$('.pref-main-chat-matevmate').html('');
						$('.pref-main-chat-matevmate').html(response);
					}
					var $elem = $('.pref-main-chat-matevmate');
					$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
				}
			},
			complete: function() {
				setTimeout(syncWager, 5000);
			}
		});
	//}
})();
// auto sync preferred sports/team banter board //		


$(document).off('click touchstart', '#banter_type_game_pref_sports').on('click touchstart', '#banter_type_game_pref_sports', function(){
	$(this).children('.sel_drop_down').toggle();
});

$("body").click(function(e) {
	if ( $(e.target).attr('id') !== "banter_type_game_pref_sports" ) {
		$("#banter_type_pref_events_dropdown").hide();
	}
});

$(document).off('click touchstart', '.place-wager-pref-sports').on('click touchstart', '.place-wager-pref-sports', function(e) {
	e.preventDefault();
	var eventID	= $(this).attr('data-pref-sport-event-id');
	
	if ( eventID ) {
		window.location.href = siteurl + "/make/bet/" + eventID;
	} else {
		generate('error', 'An unknown error occured. Please reload the page to continue');
	}
});

$(document).off('click touchstart', '.banter_type_pref_events_selector').on('click touchstart', '.banter_type_pref_events_selector', function() {
	var eventName 	= $(this).attr('data-event-type-pref-event-name');
	var eventID 	= $(this).attr('data-event-type-pref-event-id');
	var eventTime 	= $(this).attr('data-event-type-pref-event-time');
	
	$('#banter_type_pref_first_event').attr('data-event-type-pref-event-id', '');
	$('#banter_type_pref_first_event').attr('data-event-type-pref-event-id', eventID);
	$('#banter_type_pref_first_event').html('');
	$('#banter_type_pref_first_event').html(eventName);
	
	$('#banter_type_pref_sport_time').html('');
	$('#banter_type_pref_sport_time').html(eventTime);
	
	$('#pref_challenge_placer').attr('data-pref-sport-event-id', '');
	$('#pref_challenge_placer').attr('data-pref-sport-event-id', eventID);
	
	data 	= {"_token" : token, 'pref_event_id' : eventID, "check_last_pref_wager_only" : "false", "sync_msg_ps" : "false", "ps_wager_filter_type" : "game", "banter": "right"};
		
		
	$.ajax({
		url:  siteurl + "/banter-board",
		type: 'POST',
		data	: data,
		dataType: 'text',
		async : true,
		success: function(response) {
			if (response) {
				var newresponse = $(response).find('div').first().last().remove();
				console.log('div pref-chat-matevmate removed');
				$('.pref-main-chat-matevmate').html('');
				$('.pref-main-chat-matevmate').replaceWith(newresponse);
				$("#banter_type_pref_events_dropdown").hide();
				var $elem = $('.pref-main-chat-matevmate');
				$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
			}
		}
	});
});


$(document).off('click touchstart', '#bbSportsPost').on('click touchstart', '#bbSportsPost', function(e) {
	postToPSBanter	= true;
	var wagerCmt 	= $('#bbSports').val();
	var selFilter	= $('.pref_chat_selector_type_holder').attr('data-ps-filter-type');
	var lastID		= $('.pref-only-bentor').last().attr('data-pef-banter-id');
	
	if ( wagerCmt ) {
		
		$('#bbSports').val('');
		
		if ( selFilter == 'game' ) {
			var selEvent 	= $('#banter_type_pref_first_event').attr('data-event-type-pref-event-id');
			
			if ( selEvent ) {		
				var data 		= {"_token" : token, "sel_mate_id" : 0, "sel_event_id" : selEvent, "users_comment" : wagerCmt, "type" : "mysports", "ps_wager_filter_type" : selFilter, "banter": "right"};
				
				$.ajax({
					url:  siteurl + "/post-to-banter",
					type: 'POST',
					data	: data,
					dataType: 'text',
					async : true,
					success: function(response) {
						if ( response ) {
							
							if ( response == 'true' ) {
								//~ var newData = {};
		//~ 
								//~ if ( lastID ) {							
									//~ newData 	= {"_token" : token, 'pref_event_id' : selEvent, "check_last_pref_wager_only" : "true", "last_id" : lastID, "sync_msg_ps" : "true", "ps_wager_filter_type" : "game", "banter": "right"};
								//~ } else {
									//~ newData 	= {"_token" : token, 'pref_event_id' : selEvent, "check_last_pref_wager_only" : "false", "sync_msg_ps" : "true", "ps_wager_filter_type" : "game", "banter": "right"};
								//~ }
							//~ 
								//~ $.ajax({
									//~ url:  siteurl + "/banter-board",
									//~ type: 'POST',
									//~ data	: newData,
									//~ dataType: 'text',
									//~ async : true,
									//~ success: function(response) {
										//~ if (response != 'false') {
											//~ if ( lastID ) {
												//~ $('.pref-main-chat-matevmate').append(response);
											//~ } else {
												//~ $('.pref-main-chat-matevmate').html('');
												//~ $('.pref-main-chat-matevmate').html(response);
											//~ }
											//~ var $elem = $('.pref-main-chat-matevmate');
											//~ $elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
										//~ }
									//~ }
								//~ });
							} else {
								generate('error', 'Unable to post your comment right now. Please try again in some time.');
							}
							postToPSBanter = false;
						} else {
							generate('error', 'An unknown error occured. Please reload the page to continue.');
						}
					}
				});
			} else {
				generate('error', 'Unable to post your comment right now. Please try again in some time.');
			}
		}
		
	} else {
		$('#bbSports').focus();
	}
});
