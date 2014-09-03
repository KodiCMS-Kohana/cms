<div class="form-group">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>" for="<?php echo $field->name; ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>

	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<?php if($field->display == DataSource_Hybrid_Field_Primitive_Boolean::HTML_SELECT): ?>
		<div class="col-md-3 no-padding-hr">
			<?php echo Form::select($field->name, array(
				0 => __('No'),
				1 => __("Yes")
			), $value); ?>
		</div>
		<?php elseif($field->display == DataSource_Hybrid_Field_Primitive_Boolean::HTML_CHECKBOX): ?>
		<div class="checkbox">
			<label><?php echo Form::checkbox($field->name, 1, $value == 1, array('id' => $field->name)); ?></label>
		</div>
		<?php else: ?>
		<label class="radio radio-inline">
			<?php echo Form::radio($field->name, 1, $value == 1); ?> <?php echo __('Yes'); ?>
		</label>
		<label class="radio radio-inline">
			<?php echo Form::radio($field->name, 0, $value == 0); ?> <?php echo __('No'); ?>
		</label>
		<?php endif; ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>