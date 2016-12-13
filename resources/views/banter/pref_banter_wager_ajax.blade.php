<?php
if (isset($sync_msg_ps) && $sync_msg_ps == 'false') {
?>
<div class="chat_container pref-chat-matevmate">
<?php
}

if (isset($sync_msg_ps) && $sync_msg_ps == 'false') {
?>
<div class="chat pref-main-chat-matevmate">
<?php
}
?>
	<?php
		if (count($preferred_sports_data) == 0 ) {
			echo 'You dont have any events';
			
		} else if (count($pref_banter_discussion) == 0 ) {
			echo 'Start a new discussion with your mates.';
			
		}
	?>	
	@foreach($pref_banter_discussion as $discussion)
	<?php
		$person_class= "other-person";
		if ($discussion->user_id == Auth::user()->id) {
			$person_class= "your-self";
		}
	?>
	<div id="mvmso_{{ $discussion->id }}" class="person {{$person_class}} pref-only-bentor" data-pef-banter-id="{{ $discussion->id }}">
		@if($discussion->user_id == Auth::user()->id)
			<div class="chat_txt">
				<div class="text_bg">
					<i title="Delete" class="fa fa-remove delete_pref_comment" id="mvmc_<?php echo $discussion->id; ?>"></i>
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
					<i title="Delete" class="fa fa-remove delete_pref_comment" id="mvmc_<?php echo $discussion->id; ?>"></i>
-->
					{{$discussion->comment}}
				</div>
			</div>
		@endif
	</div>
	@endforeach

<?php
if (isset($sync_msg_ps) && $sync_msg_ps == 'false') {
?>
</div>
</div>
<?php
}
?>

