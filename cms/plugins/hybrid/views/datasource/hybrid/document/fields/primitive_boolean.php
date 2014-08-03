<div class="control-group">
	<label class="control-label"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="controls">
		<?php if($field->display == DataSource_Hybrid_Field_Primitive_Boolean::HTML_SELECT): ?>
		<?php echo Form::select($field->name, array(
			0 => __('No'),
			1 => __("Yes")
		), $value); ?>
		<?php elseif($field->display == DataSource_Hybrid_Field_Primitive_Boolean::HTML_CHECKBOX): ?>
		<label class="checkbox">
			<?php echo Form::checkbox($field->name, 1, $value == 1); ?>
		</label>
		<?php else: ?>
		<label class="radio inline">
			<?php echo Form::radio($field->name, 1, $value == 1); ?> <?php echo __('Yes'); ?>
		</label>

		<label class="radio inline">
			<?php echo Form::radio($field->name, 0, $value == 0); ?> <?php echo __('No'); ?>
		</label>
		<?php endif; ?>
		
		<?php if(isset($field->hint)): ?>
		<span class="help-block"><?php echo $field->hint; ?></span>
		<?php endif; ?>
	</div>
</div>