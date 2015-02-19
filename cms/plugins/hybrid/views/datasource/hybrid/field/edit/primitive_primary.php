<div class="form-group form-inline">
	<label class="control-label col-md-3" for="increment_step"><?php echo __('Increment step'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('increment_step', $field->increment_step, array(
			'class' => 'form-control', 'id' => 'increment_step', 'size' => 5, 'maxlength' => 5
		)); ?>
	</div>
</div>