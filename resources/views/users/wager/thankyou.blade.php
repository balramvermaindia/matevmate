@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
	<div class="mid_section">
		<div class="list_outer outcome_outer open_betting my-wager" style="min-height: 314px;">
		<h1><span>Honour Wagers Successfully</span></h1>
		
			<div  class="text-center" style="min-height:100px; padding-top:40px;">
				<p style="text-transform:inherit; display:block; vertical-align:top;font-size:13px; padding-left:10px;"><h2>Thank you!</h2> Thanks for honouring your wager.</p>
			</div>
				
	</div>
	</div>

@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')
@section('script')
<script type="text/javascript"> 
$(document).ready(function(){
	formId = '#honor_form'; 
	$("input").on('click', '', function() {
		var inputFieldFormError = "."+$(this).attr('id')+'formError'; 
		$(inputFieldFormError).fadeOut(150, function() {
			$(this).remove();
		});
	});
	
	$(formId).validationEngine('attach',{
		autoHidePrompt:true, 
		autoHideDelay: 5000,
		
	});
});

</script>

@endsection
