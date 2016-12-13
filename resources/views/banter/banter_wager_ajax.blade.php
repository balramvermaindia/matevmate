<?php
if (isset($game_event_display) && $game_event_display == 'true') {
	if (count($wagers_events_array) > 0) {
		$last_event = reset($wagers_events_array);
?>
<div id="banter_type_game_all_events">
	<span id="banter_type_game_first_event" data-event-type-game-event-id="{{@$last_event->id}}">{{@$last_event->name}}</span>
							
	<div class="sel_drop_down" id="banter_type_game_events_dropdown">
		@foreach($wagers_events_array as $event)
			<a href="javascript:void(0);" data-event-type-game-event-time="{{date('d M Y | h:i A', strtotime(@$event->startdatetime))  }}" data-event-type-game-event-name="{{@$event->name}}" data-event-type-game-event-id="{{@$event->id}}" class="banter_type_game_events_selector">{{@$event->name}}</a>
		@endforeach
	</div>
</div>

<?php
	}
}
?>

<?php
if (isset($sync_msg_wo) && $sync_msg_wo == 'false') {
?>
<div class="chat_container wager-chat-matevmate">
<?php
}

if (isset($event_selector) && $event_selector == 'true') {
	if (count($wagers_events_array) > 0) {
?>
<select class="wager_sel event-selector-matevmate" onchange="changeEvent(this);">
	@foreach($wagers_events_array as $event)
	<option value="{{$event->id}}">{{$event->name}}</option>
	@endforeach
</select>
<?php
	}
}
?>

<?php
if (isset($sync_msg_wo) && $sync_msg_wo == 'false') {
?>
<div class="chat wager-main-chat-matevmate">
<?php
}
?>
	<?php
		if (count($wagers_events_array) == 0 ) {
			echo 'You dont have any events';
			
		} else if (count($wager_banter_discussion) == 0 ) {
			echo 'Start a new discussion with your mates.';
			
		}
	?>	
	@foreach($wager_banter_discussion as $discussion)
	<?php
		$person_class= "other-person";
		if ($discussion->user_id == Auth::user()->id) {
			$person_class= "your-self";
		}
	?>
	<div class="person {{$person_class}} wager-only-bentor" id="mvmbo_{{ $discussion->id }}" data-mate-banter-id="{{ $discussion->id }}">
		@if($discussion->user_id == Auth::user()->id)
			<div class="chat_txt">
				<div class="text_bg">
					<i title="Delete" class="fa fa-remove delete_comment" id="mvmc_<?php echo $discussion->id; ?>"></i>
					{{$discussion->comment}}
				</div>
			</div>
			<div class="person_img">
				<div class="user">
					@if ( isset($discussion->commentBy->photo) && !empty($discussion->commentBy->photo) )
						<img src="{{ url('assets/users/img/user_profile/'.$discussion->commentBy->photo) }}">
					@else
						<img src="{{ url('assets/img/user.png') }}">
					@endif
				</div>
				<!--
				<div class="chat_time">
					{{$discussion->commentBy->firstname}}
				</div>
				!-->
				<div class="chat_time">
					{{$discussion->created_at->diffForHumans()}}
				</div>
			</div>
		@else
			<div class="person_img">
				<div class="user">
					@if ( isset($discussion->commentBy->photo) && !empty($discussion->commentBy->photo) )
						<img src="{{ url('assets/users/img/user_profile/'.$discussion->commentBy->photo) }}">
					@else
						<img src="{{ url('assets/img/user.png') }}">
					@endif
				</div>
				<!--
				<div class="chat_time">
					{{$discussion->commentBy->firstname}}
				</div>
				!-->
				<div class="chat_time">
					{{$discussion->created_at->diffForHumans()}}
				</div>
			</div>
			<div class="chat_txt">
				<div class="text_bg">
<!--
					<i title="Delete" class="fa fa-remove delete_comment" id="mvmc_<?php echo $discussion->id; ?>"></i>
-->
					{{$discussion->comment}}
				</div>
			</div>
		@endif
	</div>
	@endforeach

<?php
if (isset($sync_msg_wo) && $sync_msg_wo == 'false') {
?>
</div>
</div>
<?php
}
?>

