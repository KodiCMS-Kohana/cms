<div class="panel panel-danger panel-dark panel-body-colorful widget-profile widget-profile-centered  dashboard-widget" data-id="<?php echo $widget_id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title"><?php echo $header; ?></span>
		<button type="button" class="close widget_settings"><?php echo UI::icon('cog'); ?></button>
		<button type="button" class="close remove_widget">×</button>
		
		<?php echo $user->gravatar(70, NULL, array('class' => 'widget-profile-avatar')); ?>
		<div class="widget-profile-header">
			<span><?php echo $user->username; ?></span>
			<br />
			<?php echo HTML::mailto($user->email); ?>
		</div>
	</div>
</div>