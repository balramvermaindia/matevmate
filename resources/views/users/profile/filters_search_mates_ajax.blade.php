
	 @if( count(@$mates) )
		@foreach( $mates as $mate )
			<?php $name = @$mate->firstname . ' ' .@$mate->lastname; ?>
			<div class="mate matelen">
				<div class="mate_detail">
					<div class="cmt_pic" style="left:0px;">
						@if ( null != @$mate->photo )
							<a href="{{ url('user-profile/'.$mate->id) }}" target="_blank"><img class="avatar" src="{{ url('assets/users/img/user_profile/'.@$mate->photo) }}" style="width:100%; height:100%;"/></a>
						@else
							<a href="{{ url('user-profile/'.$mate->id) }}" target="_blank"><img class="avatar" src="{{ url('assets/img/cmt.png') }}"></a>
						@endif
					</div>
					<div class="mate_name"><a href="{{ url('user-profile/'.$mate->id) }}" target="_blank"> <?php echo ucwords(@$name); ?></a></div>
					<div class="stars"><img src="{{ url('assets/img/stars.png') }}"/></div>
					<div class="bets"><span>{{ @$mate->total_wagers }}</span>@if(@$mate->total_wagers == '0' || @$mate->total_wagers == '1') Wager @else Wagers @endif</div>
					<div class="bets_divide"><span style="color:#019c6b;">{{ @$mate->wagers_won }} won </span> / <span style="color:#ba4c4c;">{{ @$mate->wagers_lost }} lost</span></div>
				</div>

				<a href="{{ url('sports?m='. @$mate->id) }}">PLACE A WAGER</a>
		
		
				@if (in_array($mate->id, $request_sent_array))
					<a href="{{ url('/mates/pending-invitations/sent') }}" class="pull-right">VIEW INVITATION</a>
				@elseif (in_array($mate->id, $request_received_array))
					<a href="{{ url('/mates/pending-invitations') }}" class="pull-right">VIEW INVITATIONS</a>
				@elseif ( !in_array($mate->id, $final_array) )
					<a href="{{ url('add/mate/'.$mate->id) }}" class="pull-right">ADD MATE</a>
				@endif
		
			</div>

		@endforeach
	 @else
		<div class="mate" style="text-align: center; width:100% !important;">No mate found.</div>
	 @endif
			
		
		
	
 	






