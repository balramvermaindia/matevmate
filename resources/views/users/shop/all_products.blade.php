@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section no_bg_hover">
		<div class="list_outer open_betting outcome_outer">
			<h1><span>Shop</span></h1>
			<div class="list-row search-filter">
				<form method="GET" action="{{ url('shop') }}" id="search_form">
					<div class="search"><input type="text" name="index" value="<?php if(!empty($_GET['index'])) echo $_GET['index']; ?>" placeholder="Search for Product Name"></div>
					<div style="width:40%;" class="search">
						<a class="more_filter search_submit" href="javascript:void(0)" id="product_search">Search</a>
						<div style="display:none;" class="filter_popup">
							<select class="text"><option>Atleast 3 star rating</option></select>
							<select style="margin-bottom:0px;" class="text"><option>Atleast 10 bets</option></select>
						</div>
					</div>
				</form>
			</div>
				<div class="mates_outer">
			<?php
				if ( count($wager) ) {
					foreach ( $wager as $item ) {
						$name = @$item->name;
			?>			 
			<div class="mate product">
				<div class="mate_detail product_detail" style="padding:0px;">
					<div class="mate_name product_name">{{ $name }}</div>
				</div>
				
				<div class="wager">
					<a href="{{ url('shop/wager-detail/'.$item->id) }}"><img src="{{ url('assets/shop/products/'.$item->image) }}"/></a>
				</div>
				
				<div class="bets prize"><span>Price</span>${{ $item->price }}<span style="font-size:14px;"> each</span></div>
				<a class="pull-right" href="{{ url('shop/wager-detail/'.$item->id) }}" style="margin-top:0px;width:37%;">VIEW DETAIL</a>
			</div>

			<?php
				}
			} else {
			?>
				<div class="mate" style="text-align: center; width:100% !important;">Sorry! No wager was found.</div>
			<?php
			}
			?>
		</div>
	</div>
	<div class="pull-right">
		{!! $wager->appends(Request::only('index'))->render() !!}
	</div>
</div>
	
@endsection
@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on('click', '#product_search', function(e){
				e.preventDefault();
				$('#search_form').submit();
			});
		});
	</script>
@endsection
@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
