
		@if( count($mates_profile)> 0 )
			<div style="" class="">
				<div style="margin:17px;" class="scroll_outer">
<!--
					<div class="scroll vertical-align">
						<div class="list-row list-title">
							<div style="width:50%;" class="list-col">Mate</div>
							<div style="width:50%;" class="list-col">Action</div>
						</div>
-->
						@foreach( $mates_profile as $mate_profile )
							<div class="list-row">
								<div style="width:50%;" class="list-col user_rating">
									<div class="small_profile">
										@if( $mate_profile->photo == "" )
											<img src="{{ url('assets/img/cmt.png') }}">
										@else
											<a href="{{ url('user-profile/'.$mate_profile->id) }}" target="_blank" style="text-decoration:none;">
												<img src="{{ url('assets/users/img/user_profile/'.@$mate_profile->photo) }}" style="width:58px;">
											</a>
										@endif
									</div>
									<a style="text-decoration:none; color:black;" target="_blank" id="font-size" href="{{ url('user-profile/'.$mate_profile->id) }}">{{ ucfirst(@$mate_profile->firstname . ' ' .@$mate_profile->lastname) }}</a>
									<p><img style="height:12px; vertical-align:centre" src="{{ url('assets/users/img/star_rating1.png') }}"></p>
								</div>
								<div style="width:50%;" class="list-col">
									<a href="javascript:void(0);" class="pull-right select_mate" data-user="{{ $mate_profile->id }}"><i class="fa fa-check"></i> SELECT MATE</a>
								</div>
							</div>
					   @endforeach	
					</div>
				</div>
			</div>
		@else
			<div class="mate" style="text-align: center; width:100% !important;">Sorry! You don't have any mate.</div>
		@endif	
	
