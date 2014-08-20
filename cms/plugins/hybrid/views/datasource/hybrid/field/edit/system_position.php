<div class="form-group">
	<label class="control-label col-md-3" for="position"><?php echo __('Field position'); ?></label>
	<div class="col-md-9 form-inline">
		<?php echo Form::input('position', Arr::get($post_data, 'position', $field->position), array(
			'id' => 'position',
			'class' => 'form-control',
			'size' => 4,
			'maxlength' => 4
		)); ?>
	</div>
</div>