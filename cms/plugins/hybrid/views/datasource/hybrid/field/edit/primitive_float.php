<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length"><?php echo __('Field length'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('length', $field->length, array(
			'class' => 'form-control', 'id' => 'length', 'size' => 4, 'maxlength' => 4
		)); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="after_coma_num"><?php echo __('Number of decimal places'); ?></label>
		&nbsp;&nbsp;&nbsp;
		<?php echo Form::input('after_coma_num', $field->after_coma_num, array(
			'class' => 'form-control', 'id' => 'after_coma_num', 'size' => 2, 'maxlength' => 2
		)); ?>
	</div>
</div>

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="min"><?php echo __('Min'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('min', $field->min, array(
			'class' => 'form-control', 'id' => 'min', 'size' => 10, 'maxlength' => 10
		)); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<label for="max"><?php echo __('Max'); ?></label>
		&nbsp;&nbsp;&nbsp;
		<?php echo Form::input('max', $field->max, array(
			'class' => 'form-control', 'id' => 'max', 'size' => 10, 'maxlength' => 10
		)); ?>
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="primitive_default"><?php echo __('Default value'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('default', $field->default, array(
			'class' => 'form-control', 'id' => 'primitive_default', 'size' => 10, 'maxlength' => 10
		)); ?>
	</div>
</div>