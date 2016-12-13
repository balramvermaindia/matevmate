
<?php
	if ( count($mates) ) {
		foreach ( $mates as $mate ) {
			$name = @$mate->firstname . ' ' .@$mate->lastname;
?>
	<div class="mate matelen">
		<div class="mate_detail">
			<div class="cmt_pic" style="left:0px;">
				@if ( null != @$mate->photo )
					<img class="avatar" src="{{ url('assets/users/img/user_profile/'.@$mate->photo) }}">
				@else
					<img class="avatar" src="{{ url('assets/img/cmt.png') }}">
				@endif
			</div>
			<div class="mate_name"><?php echo ucwords(@$name); ?></div>
			<div class="stars"><img src="{{ url('assets/img/stars.png') }}"/></div>
			<div class="bets"><span>{{ @$mate->total_wagers }}</span>@if(@$mate->total_wagers == '0' || @$mate->total_wagers == '1') Wager @else Wagers @endif</div>
			<div class="bets_divide"><span style="color:#019c6b;">{{ @$mate->wagers_won }} win </span> / <span style="color:#ba4c4c;">{{ @$mate->wagers_lost }} lost</span></div>
		</div>
		<a href="{{ url('user-profile/' . $mate->id) }}" target="_blank">VIEW PROFILE</a>
		<a class="pull-right select_mate @if( count( @$selected_mate_arr ) && in_array(@$mate->id, @$selected_mate_arr) ) mate-selected @endif" data-user="{{ $mate->id }}" href="javascript:void(0);"><i class="fa fa-check" ></i> SELECT MATE</a>
	</div>
<?php
	}
} else {
?>
	<div class="mate" style="text-align: center; width:100% !important;">No record found.</div>
<?php
}
?>
 
 

