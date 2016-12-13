var token	  		= $("#banter_token").val();
postToWOBanter 		= false;

$(document).ready(function() {
	var $elem = $('.wager-main-chat-matevmate');
	$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
	
	$(document).off('click touchstart', '.delete_comment').on('click touchstart', '.delete_comment', function(e) {
		var banterID 	= $(this).parents('div.person').attr('data-mate-banter-id');
		var elem		= $(this).parents('div.person');
		if ( banterID ) {
			deleteAjaxFunction(banterID, '/delete/banter', token, elem, 'Are you sure you want to delete this comment?', 'Confirmation Required' );
		} else {
			generate('error', 'Unable to delete this comment right now. Please try again in some time.');
		}
	});

	$(document).off('click touchstart', '.wager_select_type_changer').on('click touchstart', '.wager_select_type_changer', function(e) {
		//e.preventDefault();
		var filterType = $(this).attr('data-wager-filter');
		
		if ( filterType == 'mate' ) {
			$('#banter_type_game_events').html('');
			$('.event-selector-matevmate').css('display', 'inline-block');
			$('.wager_type_mate').css('display', 'inline-block');
			$('.selected_wager_type_filter').html('');
			$('.selected_wager_type_filter').html('Mates');
			$('.chat_selector_type_holder').attr('data-wo-filter-type', '');
			$('.chat_selector_type_holder').attr('data-wo-filter-type', 'mate');
			
			$('.wager_type_game').css('display', 'none');
			
			var userPic 	= $('.mate-selector').attr('data-mate-pic');
			var userName 	= $('.mate-selector').attr('data-mate-name');
			var userID 		= $('.mate-selector').attr('data-mate-id');
			
			if ( userPic && userName && userID ) {
				$('.selected-mate-data-name').html('');
				$('.selected-mate-data-name').html(userName);
				$('.selected-mate-data-pic').attr('src', '');
				$('.selected-mate-data-pic').attr('src', userPic);
				
				$('.selected-mate-data').attr('data-sel-user', '');
				$('.selected-mate-data').attr('data-sel-user', userID);
				
				//var lastID	= $('.wager-only-bentor').last().attr('data-mate-banter-id');
				var data	= {};
				data 	= {"_token" : token, "mate_id" : userID, "event_selector" : "true", "check_last_wager_only" : "false", "sync_msg_wo" : "false", "wo_wager_filter_type" : "mate", "banter": "left"};
				

				$.ajax({
					url:  siteurl + "/banter-board",
					type: 'POST',
					data	: data,
					dataType: 'text',
					async : true,
					success: function(response) {
						if (response) {
							$('.wager-chat-matevmate').html('');
							$('.wager-chat-matevmate').replaceWith(response);
							var $elem = $('.wager-main-chat-matevmate');
							$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
						}
						$("#wager-select-type-dropdown").hide();
					}
				});
			} else {
				window.location.reload();
				//generate('error', 'An unknown error occured. Please reload the page to continue');
			}
		} else if ( filterType == 'game' ) {
			$('#banter_type_game_events').html('');

			$('.event-selector-matevmate').css('display', 'none');
			$('.wager_type_mate').css('display', 'none');
			$('.selected_wager_type_filter').html('');
			$('.selected_wager_type_filter').html('Games');
			$('.chat_selector_type_holder').attr('data-wo-filter-type', '');
			$('.chat_selector_type_holder').attr('data-wo-filter-type', 'game');
			
			
			
			data 	= {"_token" : token, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "false", "wo_wager_filter_type" : "game", "game_event_display" : "true", "banter": "left"};
		
		
			$.ajax({
				url:  siteurl + "/banter-board",
				type: 'POST',
				data	: data,
				dataType: 'text',
				async : true,
				success: function(response) {
					if (response) {
						
						var extractedResponse = $($.parseHTML(response)).filter("#banter_type_game_all_events").html();
						$('#banter_type_game_events').html('');
						
						if ( extractedResponse ) {
							$('#banter_type_game_events').append(extractedResponse);
							$('.wager_type_game').css('display', 'inline-block');
						}
						
						var remainingResponse = $($.parseHTML(response)).filter(".wager-chat-matevmate");
						var newresponse = $(remainingResponse).find('div').first().remove();
						
						console.log('div wager-chat-matevmate removed');
						$('.wager-main-chat-matevmate').html('');
						$('.wager-main-chat-matevmate').replaceWith(newresponse);
						var $elem = $('.wager-main-chat-matevmate');
						$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
					}
					$("#wager-select-type-dropdown").hide();
				}
			});
		}
	});
	
	// auto delete banter //
	(function checkDeletedMessage() {
		var data		= {};
		var filterType 	= $('.chat_selector_type_holder').attr('data-wo-filter-type');
		var firstID		= $('.wager-only-bentor').first().attr('data-mate-banter-id');
		var lastID		= $('.wager-only-bentor').last().attr('data-mate-banter-id');
		
		if ( filterType == 'mate' ) {
			var eventID 	= $('.event-selector-matevmate').val();
			var userID		= $('.selected-mate-data').attr('data-sel-user');

			if ( lastID ) {
				data 	= {"_token" : token, "mate_id" : userID, 'event_id' : eventID, "first_id" : firstID, "last_id" : lastID };
			}
		} else if ( filterType == 'game' ) {
			var eventID 	= $('#banter_type_game_first_event').attr('data-event-type-game-event-id');
			
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
							 $('#mvmbo_' + response[i]).remove();
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
	
	// auto sync wager only banter board //
	(function syncWager() {
		var data		= {};
		var filterType 	= $('.chat_selector_type_holder').attr('data-wo-filter-type');
		var lastID		= $('.wager-only-bentor').last().attr('data-mate-banter-id');
		
		if ( filterType == 'mate' ) {
			var eventID 	= $('.event-selector-matevmate').val();
			var userID		= $('.selected-mate-data').attr('data-sel-user');

			if ( lastID ) {
				data 	= {"_token" : token, "mate_id" : userID, 'event_id' : eventID, "event_selector" : "false", "check_last_wager_only" : "true", "last_id" : lastID, "sync_msg_wo" : "true", "wo_wager_filter_type" : "mate", "banter": "left"};
			} else {
				data 	= {"_token" : token, "mate_id" : userID, 'event_id' : eventID, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "true", "wo_wager_filter_type" : "mate", "banter": "left"};
			}
		} else if ( filterType == 'game' ) {
			var eventID 	= $('#banter_type_game_first_event').attr('data-event-type-game-event-id');
			
			if ( lastID ) {
				data 	= {"_token" : token, 'event_id' : eventID, "event_selector" : "false", "check_last_wager_only" : "true", "last_id" : lastID, "sync_msg_wo" : "true", "wo_wager_filter_type" : "game", "game_event_display" : "false", "banter": "left"};
			} else {
				data 	= {"_token" : token, 'event_id' : eventID, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "true", "wo_wager_filter_type" : "game", "game_event_display" : "false", "banter": "left"};
			}
		
		}
		//if ( postToWOBanter == false ) {
			$.ajax({
				url		: siteurl + "/banter-board",
				type	: 'POST',
				data	: data,
				dataType: 'text',
				async 	: true,
				success: function(response) {
					if (response != 'false') {
						if ( lastID ) {
							//$('.wager-main-chat-matevmate').html('');
							$('.wager-main-chat-matevmate').append(response);
						} else {
							$('.wager-main-chat-matevmate').html('');
							$('.wager-main-chat-matevmate').html(response);
						}
						
						var $elem = $('.wager-main-chat-matevmate');
						$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
					}
				},
				complete: function() {
					setTimeout(syncWager, 5000);
				}
			});
		//}
	})();
	// auto sync wager only banter board //
	
	$(document).off('click touchstart', '.banter_type_game_events_selector').on('click touchstart', '.banter_type_game_events_selector', function() {
		var eventName 	= $(this).attr('data-event-type-game-event-name');
		var eventID 	= $(this).attr('data-event-type-game-event-id');
		$('#banter_type_game_first_event').attr('data-event-type-game-event-id', '');
		$('#banter_type_game_first_event').attr('data-event-type-game-event-id', eventID);
		$('#banter_type_game_first_event').html('');
		$('#banter_type_game_first_event').html(eventName);
		
		data 	= {"_token" : token, 'event_id' : eventID, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "false", "wo_wager_filter_type" : "game", "game_event_display" : "false", "banter": "left"};
		
		
		$.ajax({
			url:  siteurl + "/banter-board",
			type: 'POST',
			data	: data,
			dataType: 'text',
			async : true,
			success: function(response) {
				if (response) {
					var newresponse = $(response).find('div').first().last().remove();
					console.log('div wager-chat-matevmate removed');
					$('.wager-main-chat-matevmate').html('');
					$('.wager-main-chat-matevmate').replaceWith(newresponse);
					$('#banter_type_game_events_dropdown').hide();
					var $elem = $('.wager-main-chat-matevmate');
					$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
				}
			}
		});
	});
	
	$(document).off('click touchstart', '.mate-selector').on('click touchstart', '.mate-selector', function() {
		var userPic 	= $(this).attr('data-mate-pic');
		var userName 	= $(this).attr('data-mate-name');
		var userID 		= $(this).attr('data-mate-id');
		
		if ( userPic && userName && userID ) {
			$('.selected-mate-data-name').html('');
			$('.selected-mate-data-name').html(userName);
			$('.selected-mate-data-pic').attr('src', '');
			$('.selected-mate-data-pic').attr('src', userPic);
			
			$('.selected-mate-data').attr('data-sel-user', '');
			$('.selected-mate-data').attr('data-sel-user', userID);
			
			//var lastID	= $('.wager-only-bentor').last().attr('data-mate-banter-id');
			var data	= {};
			data 	= {"_token" : token, "mate_id" : userID, "event_selector" : "true", "check_last_wager_only" : "false", "sync_msg_wo" : "false", "wo_wager_filter_type" : "mate", "banter": "left"};
			

			$.ajax({
				url:  siteurl + "/banter-board",
				type: 'POST',
				data	: data,
				dataType: 'text',
				async : true,
				success: function(response) {
					if (response) {
						$('.wager-chat-matevmate').html('');
						$('.wager-chat-matevmate').replaceWith(response);
						var $elem = $('.wager-main-chat-matevmate');
						$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
					}
					$("#wager-select-mate-dropdown").hide();
				}
			});
		} else {
			generate('error', 'An unknown error occured. Please reload the page to continue');
		}
	})
	
	
	$(document).off('click touchstart', '#bbWagerPost').on('click touchstart', '#bbWagerPost', function(e) {
		postToWOBanter	= true;
		var wagerCmt 	= $('#bbWager').val();
		var selFilter	= $('.chat_selector_type_holder').attr('data-wo-filter-type');
		var lastID		= $('.wager-only-bentor').last().attr('data-mate-banter-id');
		
		if ( wagerCmt ) {
			$('#bbWager').val('');
			
			if ( selFilter == 'mate' ) {
				var selEvent 	= $('.event-selector-matevmate').val();
				var selUser	 	= $('.selected-mate-data').attr('data-sel-user');
				
				
				if ( selEvent && selUser ) {
					var data 	= {"_token" : token, "sel_mate_id" : selUser, "sel_event_id" : selEvent, "users_comment" : wagerCmt, "type" : "mywager", "wo_wager_filter_type" : selFilter, "banter": "left"};
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
										//~ newData 	= {"_token" : token, "mate_id" : selUser, 'event_id' : selEvent, "event_selector" : "false", "check_last_wager_only" : "true", "last_id" : lastID, "sync_msg_wo" : "true", "wo_wager_filter_type" : "mate", "banter": "left"};
									//~ } else {
										//~ newData 	= {"_token" : token, "mate_id" : selUser, 'event_id' : selEvent, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "true", "wo_wager_filter_type" : "mate", "banter": "left"};
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
													//~ //$('.wager-main-chat-matevmate').html('');
													//~ $('.wager-main-chat-matevmate').append(response);
												//~ } else {
													//~ $('.wager-main-chat-matevmate').html('');
													//~ $('.wager-main-chat-matevmate').html(response);
												//~ }
												//~ var $elem = $('.wager-main-chat-matevmate');
												//~ $elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
											//~ }
										//~ }
									//~ });
								} else {
									generate('error', 'Unable to post your comment right now. Please try again in some time.');
								}
								
								postToWOBanter = false;
							} else {
								generate('error', 'An unknown error occured. Please reload the page to continue.');
							}
						}
					});
				} else {
					generate('error', 'Unable to post your comment right now. Please try again in some time.');
				}
			} else if ( selFilter == 'game' ) {
				var selEvent 	= $('#banter_type_game_first_event').attr('data-event-type-game-event-id');
				
				if ( selEvent ) {
					var data 		= {"_token" : token, "sel_mate_id" : 0, "sel_event_id" : selEvent, "users_comment" : wagerCmt, "type" : "mywager", "wo_wager_filter_type" : selFilter, "banter": "left"};
					
					$.ajax({
						url:  siteurl + "/post-to-banter",
						type: 'POST',
						data	: data,
						dataType: 'text',
						async : true,
						success: function(response) {
							if ( response ) {
								
								if ( response == 'true' ) {
									var newData = {};
			
									if ( lastID ) {
										newData 	= {"_token" : token, 'event_id' : selEvent, "event_selector" : "false", "check_last_wager_only" : "true", "last_id" : lastID, "sync_msg_wo" : "true", "wo_wager_filter_type" : "game", "game_event_display" : "false", "banter": "left"};
									} else {
										newData 	= {"_token" : token, 'event_id' : selEvent, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "true", "wo_wager_filter_type" : "game", "game_event_display" : "false", "banter": "left"};
									}
								
									$.ajax({
										url:  siteurl + "/banter-board",
										type: 'POST',
										data	: newData,
										dataType: 'text',
										async : true,
										success: function(response) {
											if (response != 'false') {
												if ( lastID ) {
													//$('.wager-main-chat-matevmate').html('');
													$('.wager-main-chat-matevmate').append(response);
												} else {
													$('.wager-main-chat-matevmate').html('');
													$('.wager-main-chat-matevmate').html(response);
												}
												var $elem = $('.wager-main-chat-matevmate');
												$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
											}
										}
									});
								} else {
									generate('error', 'Unable to post your comment right now. Please try again in some time.');
								}
							} else {
								generate('error', 'An unknown error occured. Please reload the page to continue.');
							}
						}
					});
				} else {
					generate('error', 'Unable to post your comment right now. Please try again in some time.');
				}
			} // game end
			
		} else {
			$('#bbWager').focus();
		}
	});
	
	$(document).off('click touchstart', '#wager-select-type').on('click touchstart', '#wager-select-type', function(){
		$(this).children('.sel_drop_down').toggle();
	});
													
	$(document).off('click touchstart', '#wager-select-mate').on('click touchstart', '#wager-select-mate', function(){
		$(this).children('.sel_drop_down').toggle();
	});
	$(document).off('click touchstart', '#banter_type_game_events').on('click touchstart', '#banter_type_game_events', function(){
		$(this).children('.sel_drop_down').toggle();
	});
});

$("body").click(function(e){
	if ( $(e.target).attr('id') !== "wager-select-type" ) {
		$("#wager-select-type-dropdown").hide();
	}
	if ( $(e.target).attr('id') !== "wager-select-mate" ) {
		$("#wager-select-mate-dropdown").hide();
	}
	if ( $(e.target).attr('id') !== "banter_type_game_events" ) {
		$("#banter_type_game_events_dropdown").hide();
	}
});

function changeEvent(context)
{
	var data	= {};
	var eventID = context.value;
	var userID	= $('.selected-mate-data').attr('data-sel-user');
	var lastID	= $('.wager-only-bentor').last().attr('data-mate-banter-id');
	
	data 	= {"_token" : token, "mate_id" : userID, 'event_id' : eventID, "event_selector" : "false", "check_last_wager_only" : "false", "sync_msg_wo" : "false", "wo_wager_filter_type" : "mate", "banter": "left"};
	
	
	$.ajax({
		url:  siteurl + "/banter-board",
		type: 'POST',
		data	: data,
		dataType: 'text',
		async : true,
		success: function(response) {
			if (response) {
				var newresponse = $(response).find('div').first().last().remove();
				console.log('div wager-chat-matevmate removed');
				$('.wager-main-chat-matevmate').html('');
				$('.wager-main-chat-matevmate').replaceWith(newresponse);
				var $elem = $('.wager-main-chat-matevmate');
				$elem.animate({scrollTop: $elem.prop('scrollHeight')}, 500);
			}
		}
	});
}
