<div class="form-group">
	<label class="control-label" for="primitive_default"><?php echo __( 'Default value' ); ?></label>
	<div class="controls">
		<?php
		echo Form::input( 'default', $field->default, array(
			'class' => 'datetimepicker', 
			'id' => 'primitive_default',
			'size' => 10,
			'autocomplete' => 'off'
		) );
		?>
	</div>
</div>

<hr />
<div class="form-group">
	<div class="controls">
		<label class="checkbox"><?php echo Form::checkbox('set_current', 1, $field->set_current == 1, array('id' => 'set_current' )); ?> <?php echo __('Current datetime'); ?></label>
	</div>
</div>