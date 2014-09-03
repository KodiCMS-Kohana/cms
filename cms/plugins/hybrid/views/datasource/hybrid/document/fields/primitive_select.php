<div class="form-group form-inline">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<?php echo Form::select($field->name, $field->get_options(), $value, array(
			'id' => $field->name
		)); ?>
		
		<?php if($field->custom_option): ?>
		<br /><br />
		<?php echo Form::input($field->name . '_custom', NULL, array(
				'id' => $field->name . '_custom',
				'maxlength' => 50,
				'placeholder' => __('Custom value'),
				'class' => 'form-control'
		)); ?>
		<?php endif; ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>