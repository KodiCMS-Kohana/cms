<div class="control-group">
	<label class="control-label" for="primitive_default"><?php echo __( 'Default value' ); ?></label>
	<div class="controls">
		<?php
		echo Form::input( 'default', $field->default, array(
			'class' => 'datepicker', 'id' => 'primitive_default', 'size' => 10
		) );
		?>
	</div>
</div>

<hr />
<div class="control-group">
	<div class="controls">
		<label class="checkbox"><?php echo Form::checkbox('set_current', 1, $field->set_current == 1, array('id' => 'set_current' )); ?> <?php echo __('Current date'); ?></label>
	</div>
</div>