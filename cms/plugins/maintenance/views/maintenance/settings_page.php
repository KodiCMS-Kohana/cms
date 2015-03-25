<div class="panel-heading panel-toggler" data-target-spoiler=".maintenance-spoiler" data-icon="wrench">
	<span class="panel-title"><?php echo __('Maintenance mode'); ?></span>
</div>
<div class="panel-body panel-spoiler maintenance-spoiler">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Enable maintenance mode'); ?></label>
		<div class="controls">
			<?php echo Form::select('plugin[maintenance_mode]', Form::choices(), $plugin->get('maintenance_mode')); ?>
		</div>
	</div>
</div>