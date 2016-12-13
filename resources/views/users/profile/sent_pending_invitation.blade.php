@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
 			
		<div class="profile_outer">
			@include('sections.matesTabs')
			
			<div class="profile_outer">
				@include('sections.pendingInvitationTabs')
			
			 <div class="mates_outer">
				 <?php
					if ( count($matesRequests) ) {
						foreach ( $matesRequests as $matesRequest ) {
							$name = @$matesRequest->sentRequestMateProfile->firstname . ' ' .$matesRequest->sentRequestMateProfile->lastname;
				?>
				<div class="mate">
					<div class="mate_detail">
						<div class="cmt_pic" style="left:0px;">
							@if ( null != @$matesRequest->sentRequestMateProfile->photo )
								<a href="{{ url('user-profile/'.$matesRequest->sentRequestMateProfile->id) }}" target="_blank"><img class="avatar" style="width:100%; height:100%;" src="{{ url('assets/users/img/user_profile/'.@$matesRequest->sentRequestMateProfile->photo) }}"></a>
							@else
								<img class="avatar" src="{{ url('assets/img/cmt.png') }}">
							@endif
						</div>
						<div class="mate_name"><a href="{{ url('user-profile/'.$matesRequest->sentRequestMateProfile->id) }}" target="_blank"><?php echo ucwords(@$name); ?></a></div>
						<div class="stars"><img src="{{ url('assets/img/stars.png') }}"/></div>
						<div class="bets"><span>{{ @$matesRequest->total_wagers }}</span>@if(@$matesRequest->total_wagers == '0' || @$matesRequest->total_wagers == '1')Wager @else Wagers @endif</div>
						<div class="bets_divide"><span style="color:#019c6b;">{{ @$matesRequest->wagers_won }} won </span> / <span style="color:#ba4c4c;">{{ @$matesRequest->wagers_lost }} lost</span></div>
						
					</div>
					
					<a href="{{ url('user-profile/'.$matesRequest->sentRequestMateProfile->id) }}" style="text-transform:uppercase;">View Profile</a>
					<a style="text-transform:uppercase;" href="javascript:void(0)" class="pull-right" onClick= "deleteFunction('{{ url('mates/cancel/request/'.$matesRequest->id) }}','Are you sure you want to cancel this request?','Confirmation Required')">Cancel Request</a>
					
				</div>
			<?php
				}
			} else {
			?>
				<div class="mate" style="text-align: center; width:100% !important;">Sorry! You havn't sent any invitation.</div>
			<?php
			}
			?>
			 </div>
			 

		</div>
		</div>
		
	</div>
 	<style>
		.list-col img{
			margin-left: 0px;
		}
 	</style>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
