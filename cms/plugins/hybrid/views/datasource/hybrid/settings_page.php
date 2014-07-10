<div class="widget-header spoiler-toggle" data-spoiler=".hybrid-spoiler" data-icon="cog">
	<h3><?php echo __('Hybrid settings'); ?></h3>
</div>

<div class="widget-content spoiler hybrid-spoiler">
	<div class="alert alert-warning">
		<i class="icon icon-lightbulb"></i> <?php echo __('To bind profile section you need to create a field `:field`', array(':field' => 'profile_id')); ?>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="user_profile_ds_id"><?php echo __( 'User profile section' ); ?></label>
		<div class="controls">
			<?php echo Form::select('plugin[user_profile_ds_id]', $sections, $plugin->get('user_profile_ds_id')); ?>
		</div>
	</div>
</div>