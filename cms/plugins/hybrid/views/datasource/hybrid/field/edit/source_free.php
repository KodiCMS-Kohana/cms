<div class="form-group">
	<label class="control-label" for="primitive_default"><?php echo __( 'Widget inject key' ); ?></label>
	<div class="controls">
		<?php
		echo Form::input( 'inject_key', $field->inject_key, array(
			'class' => 'input-xlarge', 'id' => 'inject_key'
		) );
		?>
	</div>
</div>