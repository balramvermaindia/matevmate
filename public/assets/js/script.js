$(function() {

	(function worker() {
		var data 	= "worker=yes";
		$.ajax({
			url:  siteurl + "/notifications/sync",
			type: 'GET',
			data	: data,
			dataType: 'json',
			success: function(response) {
				var total = $.trim(response);
				if ( total > 0 ) {
					$('#mateVmateJewel').html('');
					$('#mateVmateJewel').html(total);
					$('#mateVmateJewel').css('display', 'block');
					$('#mateVmateJewelCell').html('');
					$('#mateVmateJewelCell').html(total);
					$('#mateVmateJewelCell').css('display', 'block');
				} else if ( total == 0 ) {
					$('#mateVmateJewel').html('');
					$('#mateVmateJewel').css('display', 'none');
					$('#mateVmateJewelCell').html('');
					$('#mateVmateJewelCell').css('display', 'none');
				}
			},
			complete: function() {
			setTimeout(worker, 12000);
			}
		});
	})();

});
function generate(type, msg)
{
	var n = noty({
	text : msg,
	type : type,
	dismissQueue: true,
	timeout : 10000,
	closeWith : ['click'],
	layout : 'bottomRight',
	theme : 'defaultTheme',
	maxVisible : 10
	});
	console.log('html: ' + n.options.id);
}
function deleteFunction(curr_url,text,title){
			
	$.confirm({
	text: text,
	title: title,
	confirm: function(button) {
		window.location.href= curr_url; 
	},
	cancel: function(button) {
	// nothing to do
	},
	confirmButton: "Yes I am",
	cancelButton: "No",
	post: true,
	confirmButtonClass: "btn-danger",
	cancelButtonClass: "btn-default",
	dialogClass: "modal-dialog modal-lg custom-confirm-matevmate", // Bootstrap classes for large modal
	className: "medium"
	});
}

function deleteAjaxFunction(itemID, url, token, elemToHide, text, title)
{
	$.confirm({
		text: text,
		title: title,
		confirm: function(button) {
			var data 	= { "itemID" : itemID, "_token" : token };
			$.ajax({
				url:  	siteurl + url,
				type: 	'POST',
				data	: data,
				dataType: 'text',
				success: function(response) {
					if ( response.trim() == 'success' ) {
						elemToHide.remove();
						generate('success', 'Comment deleted successfully.');
					} else {
						generate('error', 'Unable to delete this comment right now. Please try again in some time.');
					}
				}
			});
		},
		cancel: function(button) {
			return false;
		},
		confirmButton: "Yes I am",
		cancelButton: "No",
		//post: true,
		confirmButtonClass: "btn-danger",
		cancelButtonClass: "btn-default",
		dialogClass: "modal-dialog modal-lg custom-confirm-matevmate", // Bootstrap classes for large modal
		className: "medium"
	});
}
