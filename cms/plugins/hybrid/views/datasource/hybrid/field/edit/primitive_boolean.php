<div class="form-group">
	<label class="control-label col-md-3"><?php echo __('Design'); ?></label>
	<div class="col-md-3">
		<?php echo Form::select('display', $field->display_types(), $field->display); ?>
	</div>
</div>
<hr />
<div class="form-group">
	<label class="control-label col-md-3"><?php echo __('Default value'); ?></label>
	<div class="col-md-2">
		<?php echo Form::select('default', array(
			0 => __('No'),
			1 => __("Yes")
		), $field->default); ?>
	</div>
</div>