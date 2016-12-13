var token 		= $("#banter_token").val();

$(document).ready(function() {
	
	timer();
	
	$(document).off('click', '.right_banter_cmt_btn').on('click', '.right_banter_cmt_btn', function() {
		 clearTimeout(myTimeOut);
		 $(this).parents('div.right_cmt_btn_container').hide();
		 $(this).parents('div.right_cmt_btn_container').next('div.cmt_box_container').show();
	});
	
	$(document).off('click', '.cancel_posting_cmt').on('click', '.cancel_posting_cmt', function() {
		 $(this).parents('div.right_cmt_btn_container').show();
		 $(this).parents('div.right_cmt_btn_container').next('div.cmt_box_container').hide();
		 timer();
	});
	
	//~ $(document).off('mouseenter', '.right_banter_cmt_btn').on('mouseenter', '.right_banter_cmt_btn', function() {
		 //~ clearTimeout(myTimeOut);
	//~ });
	//~ 
	//~ $(document).off('mouseleave', '.right_banter_cmt_btn').on('mouseleave', '.right_banter_cmt_btn', function() {
		//~ timer();
	//~ });
	
	$(document).off('click', '.post_to_right_banter').on('click', '.post_to_right_banter', function() {
		var data 		= {};
		var selEvent 	= $(this).attr('data-event');
		var selUser 	= $(this).attr('data-mate');
		var selchallenge = $(this).attr('data-challenge');
		var wagerCmt	= $(this).parents('div.cmt_box_container').children('.right_cmt_box').val();
		var data 		= {"_token" : token, "sel_mate_id" : selUser, "sel_event_id" : selEvent, "users_comment" : wagerCmt, "type" : "mywager", "wo_wager_filter_type" : 'mate', "banter": "left", "selchallenge": selchallenge};
		
		if ( wagerCmt ) {
			$.ajax({
				url:  siteurl + "/post-to-banter",
				type: 'POST',
				data	: data,
				dataType: 'text',
				async : true,
				success: function(response) {
					if ( response ) {
						if ( response == 'true' ) {
							$(this).parents('div.right_cmt_btn_container').show();
							$(this).parents('div.right_cmt_btn_container').next('div.cmt_box_container').hide();
							timer();
						} else {
							generate('error', 'Unable to post your comment right now. Please try again in some time.');
						}
					} else {
						generate('error', 'Unable to post your comment right now. Please try again in some time.');
					}
				}
			});
		} else {
			$(this).parents('div.cmt_box_container').children('.right_cmt_box').focus();
		}
	});
	
});

function timer()
{
	// load right banter //
	if (token) {
		(function load() {
			var data		= {};
			data 			= {"_token" : token };
			$.ajax({
				url		: siteurl + "/right-banter-board",
				type	: 'POST',
				data	: data,
				dataType: 'text',
				async 	: true,
				success: function(response) {
					$('#right_side_loder').hide();
					if ( response ) {
						
						$('.right_comment_only').html(response);
						$('.right-banter-container').show();
					}
				},
				complete: function() {
					myTimeOut = setTimeout(load, 10000);
				}
			});
			
		})();
	}
	// load right banter //
}
