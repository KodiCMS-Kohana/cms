<div class="form-group">
	<label class="control-label col-md-3" for="hint"><?php echo __('Field hint'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('hint', Arr::get($post_data, 'hint', $field->hint), array(
			'id' => 'hint',
			'class' => 'form-control'
		)); ?>
	</div>
</div>