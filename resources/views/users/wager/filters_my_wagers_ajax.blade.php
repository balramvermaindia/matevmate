
<div class="@if( count($summary)>0 ) list_outer  @endif">
	<div class="scroll_outer">
		<div class="scroll">
			@if( count($summary)>0 )
				<a class="list-row list-title" style="cursor:default !important; text-decoration:none; color:black;">
					<div class="list-col" style="width:25%;">Mate Name</div>
					<div class="list-col padding_left_match" style="width:35%;">Match</div>
					<div class="list-col" style="width:25%;">Wager</div>
					<div class="list-col" style="width:15%;">Status</div>
				</a>
				<div class="load-more-wagers">
					@foreach( $summary as $sum )
						<a class="list-row wagerlen" href="{{ url('challenge-detail/'.$sum->id) }}" style="color:black">
							@if(Auth::user()->id == $sum->user_id)
								<div class="list-col" style="width:25%;">
									{{ ucfirst(@$sum->mate->firstname) }} {{ ucfirst(@$sum->mate->lastname) }}
								</div>
							@else
								<div class="list-col" style="width:25%;">
								{{ ucfirst(@$sum->user->firstname) }} {{ ucfirst(@$sum->user->lastname) }}
							</div>
							@endif
							<div class="list-col" style="width:35%;">
								<?php $img_url = getSportsImageBySportsID( @$sum->event->sports->id ); ?>
								<img class="sport-icon" src='{{ url("$img_url") }}' style="display:inline-block"/>
								<div class="match">
								<span>{{ @$sum->event->name }}</span>
								<p>
									<i aria-hidden="true" class="fa fa-calendar"></i>
									{{ showDateTime(@$sum->event->startdatetime,@$sum->event->timezone, "D d M h:i A") }}
								</p>
								</div>
							</div>
							<div class="list-col" style="width:25%;">
								<p>{{ @$sum->wager_amount }}</p>
							</div>
							<?php 
								$status 	= '';
								if($sum->challenge_status == 'awaiting'){
									$status = 'Awaiting Acceptance';
								}
								if($sum->challenge_status == 'won' || $sum->challenge_status == 'lost'){
									if(Auth::user()->id == $sum->winner_user_id){
										$status = 'won';
									}else{
										$status = 'lost';
									}
								}
								if($sum->challenge_status == 'pending'){
									$status = 'Pending';
								}
								if($sum->challenge_status == 'rejected'){
									$status = 'Rejected';
								}
								if(@$sum->challenge_status == 'cancelled'){
									$status = 'Cancelled';
								}
							?>
							<div class="list-col text-right" style="width:15%;">
								<div style="width:30px;">{{ ucwords(@$status) }}</div>
							</div>
						</a>
					@endforeach
				</div>
			@else
				<div class="mate no_record_found" style="text-align: center; margin-top:14px;">Sorry! No record found.</div>
			@endif
		</div>
	</div>					
</div>
 		
 	
 	



