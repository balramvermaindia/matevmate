
	<?php
		$challengeMate	= Session::get('challenMate');
		$selectedMateCount 	= count($challengeMate['selectedMates']);
	?>

	@if ( count($wager) ) 
		@foreach ( $wager as $item ) 
			<div class="mate product">
				<div class="mate_detail product_detail" style="padding:0px;">
					<div class="mate_name product_name">{{ @$item->name }}</div>
				</div>
				
				<div class="wager">
					<img src="{{ url('assets/shop/products/'.$item->image) }}"/>
				</div>
				
				<div class="bets prize"><span>Prize</span>${{ $item->price }}</div>
				@if($selectedMateCount > 1)
					<a class="pull-right select_wager" href="javascript:void(0);" style="margin-top:0px;" data-toggle="modal" data-target="#myModal" data-wager="{{ $item->id }}" data-backdrop="static">SELECT WAGER</a>
				@else
					<a class="pull-right sel-wager" href="javascript:void(0);" style="margin-top:0px;" data-wager="{{ $item->id }}">SELECT WAGER</a>
				@endif
			</div>
		@endforeach
	@else 
		<div class="mate" style="text-align: center; width:100% !important;">No Record Found.</div>
	@endif
