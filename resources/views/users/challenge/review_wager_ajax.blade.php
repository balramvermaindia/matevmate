<div class="challenge_info">
<div class="challenge_row list-row">
	<div class="list-col">
	<label>Match:</label>
	</div>
	<?php $img_url = getSportsImageBySportsID( @$event->betfair_sports_id ); ?>
	<div class="list-col text-right">
	<span><img src="{{ url($img_url) }}"/>{{ $event->name }}</span>
	<p>
		<i aria-hidden="true" class="fa fa-calendar"></i> 
		{{ showDateTime($event->startdatetime,$event->timezone, "d M, Y | h:i A") }}
	</p>
	</div>
</div>
<div class="challenge_row list-row">
	<div class="list-col">
	<label>My wager is on:</label>
	</div>
	<div class="list-col text-right">
	<span>{{ $event->$selectedTeam }} to Win</span>
	</div>
</div>
<div class="challenge_row list-row">
	<div class="list-col">
	<label>Wager made with:</label>
	</div>
	<div class="list-col text-right">
	<span>
		<?php
			foreach ($mate as $mates) {
				echo ucwords(@$mates['mate']->firstname .' '.@$mates['mate']->lastname). '<br>';
			}
		?>
		
		
	</span>
	</div>
</div>
<div class="challenge_row list-row">
	<div class="list-col">
	<label>Wager at stake:</label>
	</div>
	<div class="list-col text-right">
	<span>
		<?php  echo "AUD ".number_format($wager_amount,2); ?>
	</span>
	</div>
</div>
</div>

<div class="cmts continue text-right" style="margin:20px;">
	<button type="button" class="accpet_btn btn" style="color:white;display: inline-block;font-family:open_sanssemibold;font-size: 14px;padding: 8px 20px;" id="place_wager">Continue </button>
	
	<a class="cmt_btn" href="{{ url('/sports') }}" style="margin-right:10px;">Cancel</a></div>

@section('script')
<script>
$('#myModal').modal('hide');
$('body').removeClass('modal-open');
$('.modal-backdrop').remove();
$('.outcome_outer').children('h1').children('a.accpet_btn').css('display', 'none');

$(document).off('click', '.accpet_btn').on('click', '.accpet_btn', function() {
	var data			= {};
	var editEvent		= getParameterByName('edit');
	var selectedEvent	= getParameterByName('c');
	
	$('#place_wager').attr('disabled', 'disabled');
	$('.accpet_btn').attr('disabled', 'disabled');
	
	if ( editEvent && selectedEvent ) {
		data 	= {"_token":"{{ csrf_token() }}", "edit": "true", "hash": selectedEvent};
	} else {
		data 	= {"_token":"{{ csrf_token() }}", "edit": "false"};
	}
	
	$.ajax({
		url:  siteurl + "/challenge/mate",
		type: 'POST',
		data	: data,
		dataType: 'text',
		async : true,
		success: function(response) {
			if (response) {
				if (response == 'success') {
					window.location.href = "{{ url('/wagers') }}";
				} else {
					generate('error', 'An unknown error occured. Please reload the page to continue');
				}
			}
		}
	});
});

function getParameterByName(name, url)
{
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

</script>
