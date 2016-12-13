@section('usersRightSideNavigation')

	<div class="board">
		<div id="right_side_loder" class="text-center">
			<img src="{{ url('assets/img/loading.gif') }}">
		</div>
		
		<div id="right_side_banter" class="right-banter-container">
			<div>
				<h1>Latest from Banter Board</h1>
				<select class="sel-board">
					<option>Banter with Mates</option>
<!--
					<option>Banter on my Open Wagers</option>
-->
				</select>
				<div id="rightSideDiv" style="height:92%;">
					<div class="comment_inner right_comment_only">
						
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="_token" id="banter_token" value="{{ csrf_token() }}" />
	</div>
	
@endsection
