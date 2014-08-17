<div class="form-group">
	<label class="control-label col-md-3"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<?php if($field->display == DataSource_Hybrid_Field_Primitive_Boolean::HTML_SELECT): ?>
		<?php echo Form::select($field->name, array(
			0 => __('No'),
			1 => __("Yes")
		), $value); ?>
		<?php elseif($field->display == DataSource_Hybrid_Field_Primitive_Boolean::HTML_CHECKBOX): ?>
		<div class="checkbox">
			<label><?php echo Form::checkbox($field->name, 1, $value == 1); ?></label>
		</div>
		<?php else: ?>
		<label class="radio radio-inline">
			<?php echo Form::radio($field->name, 1, $value == 1); ?> <?php echo __('Yes'); ?>
		</label>

		<label class="radio radio-inline">
			<?php echo Form::radio($field->name, 0, $value == 0); ?> <?php echo __('No'); ?>
		</label>
		<?php endif; ?>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>
	</div>
</div>