<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length"><?php echo __('Field length'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('length', $field->length, array(
			'class' => 'form-control', 'id' => 'length', 'size' => 4, 'maxlength' => 4
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
		<?php echo Form::input('default', $field->default_value(), array(
			'class' => 'form-control', 'id' => 'primitive_default', 'size' => 10, 'maxlength' => 10
		)); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('auto_increment', 1, $field->auto_increment == 1, array(
					'id' => 'auto_increment'
				)); ?> <?php echo __('Auto increment'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="increment_step"><?php echo __('Increment step'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('increment_step', $field->increment_step, array(
			'class' => 'form-control', 'id' => 'increment_step', 'size' => 5, 'maxlength' => 5
		)); ?>
	</div>
</div>