<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length"><?php echo __('Field length'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input( 'length', $field->length, array(
			'class' => 'input-mini', 'id' => 'length'
		) ); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="after_coma_num"><?php echo __('Number of decimal places'); ?></label>
		&nbsp;&nbsp;&nbsp;
		<?php echo Form::input( 'after_coma_num', $field->after_coma_num, array(
			'class' => 'input-mini', 'id' => 'after_coma_num'
		) ); ?>
	</div>
</div>

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="min"><?php echo __('Min'); ?></label>
	<div class="col-md-9">
		 <?php echo Form::input( 'min', $field->min, array(
			'class' => 'input-mini', 'id' => 'min'
		) ); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="max"><?php echo __('Max'); ?></label>
		&nbsp;&nbsp;&nbsp;
		<?php echo Form::input( 'max', $field->max, array(
			'class' => 'input-mini', 'id' => 'max'
		) ); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default"><?php echo __( 'Default value' ); ?></label>
	<div class="col-md-9">
		<?php
		echo Form::input( 'default', $field->default, array(
			'class' => 'input-xlarge', 'id' => 'primitive_default'
		) );
		?>
	</div>
</div>