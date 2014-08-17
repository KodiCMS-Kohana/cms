<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default"><?php echo __( 'Default value' ); ?></label>
	<div class="col-md-9">
		<?php echo Form::input( 'default', $field->default, array(
			'class' => 'input-xxlarge'
		) ); ?>
	</div>
</div>