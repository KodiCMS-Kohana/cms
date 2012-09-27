<fieldset>
	<legend><?php echo __( 'Maintenance mode' ); ?></legend>
	
	<div class="control-group">
		<div class="controls">
			<div class="checkbox">
				<label><?php echo Form::checkbox('plugin[enable_maintenance_mode]', 'yes', $plugin->get('enable_maintenance_mode', 'no') == 'yes'); ?> <?php echo __('Enable'); ?></label>
			</div>
		</div>
	</div>
</fieldset>