<div class="panel-heading" data-icon="cog">
	<span class="panel-title"><?php echo __('Hybrid settings'); ?></span>
</div>
<div class="note note-info">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('To bind profile section you need to create a field `:field`', array(':field' => 'profile_id')); ?>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="user_profile_ds_id"><?php echo __( 'User profile section' ); ?></label>
		<div class="col-md-3">
			<?php echo Form::select('setting[user_profile_ds_id]', $plugin->sections(), $plugin->get('user_profile_ds_id')); ?>
		</div>
	</div>
</div>