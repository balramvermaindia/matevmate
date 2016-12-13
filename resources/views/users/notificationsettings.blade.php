@extends('layouts.usersApp')

@extends('sections.usersHeaderSection')

@extends('sections.usersLeftSideNavigation')

@section('usersContent')
<div class="mid_section">
 			
<div class="list_outer outcome_outer">
	<h1><span>Notification Settings</span></h1>
	
	<div class="profile_title" style="margin:10px">
		<span>What you get notified about</span>
	</div>
	<form name="settings_form" method="POST" action="{{ url('update/settings') }}">
		{{ csrf_field() }}
		<?php
			if ( count($availableSettings) ) {
				foreach ( $availableSettings as $availableSetting ) {
		?>
			<div class="notification-setting_outer">
				<div class="notifi">
					<span><?php echo ucfirst(@$availableSetting->setting); ?></span>
					<label class="checkbox-inline pull-right">
						<?php
							if (in_array($availableSetting->id, $singleArray)) {
								$selected = 'checked';
							} else {
								$selected = '';
							}
						?>
					  <input <?php echo @$selected; ?> value="<?php echo $availableSetting->id; ?>" name="settings[]" data-toggle="toggle" type="checkbox">
					</label>
				</div>
			</div>
		<?php
			}
		}
		?>
		<div class="form-row mobile-padding" style="padding:13px;">
			<input type="submit" style="border:none; margin-right:2px;" value="SUBMIT" name="submit" class="btn_blue">
			<a href="{{ url('/notifications') }}" class="black_blue">CANCEL</a>
		</div>
	</form>
</div>
</div>
@endsection

@extends('sections.usersRightSideNavigation')
@extends('sections.usersFooterSection')

