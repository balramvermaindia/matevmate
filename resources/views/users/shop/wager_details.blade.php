@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
		<div class="list_outer outcome_outer detail_outer">
			<h1>
				<span>Wager Detail</span>
				<a class="pull-right btn accpet_btn" href="{{ url('/shop') }}" style="font-family:ubuntumedium;color:#fff;margin:5px;padding:6px 15px;">Back</a>
			</h1>
			<div class="list-row">
				<div class="list-col wager-detail-image">
					<img src="{{ url('assets/shop/products/'.$wager->image) }}" style="height:auto;vertical-align:top;max-height:300px"/>
				</div>
				<div class="list-col">
					<div style="padding:0px 15px;">
						<div class="profile_title" style="margin-left:10px;margin-top:10px;">
							<span>{{ @$wager->name }}</span>
						</div>
						<div class="bets prize detail_prize text-right" style="margin-left:10px; margin-bottom:10px;"><span>Prize</span>${{ @$wager->price }}<span style="font-size:14px;">each</span></div>
						<p style="text-transform:inherit; display:block; vertical-align:top;font-size:13px; padding-left:10px;">{{ @$wager->description }}</p>
					
						<div class="sponsor">
							<label style="vertical-align: middle;">Our Partner:</label><a traget="_blank" style="display: inline-block;vertical-align: middle;width: auto; cursor:default;"><img src="{{ url('assets/users/img/shortys_logo.png')}}"/></a>
						</div>
					</div>
				</div>
			
			</div>					
		</div>
	</div>
@endsection
@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

