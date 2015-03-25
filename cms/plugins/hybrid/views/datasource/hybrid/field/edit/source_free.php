<div class="form-group form-inline">
	<label class="control-label col-md-3" for="inject_key"><?php echo __('Widget inject key'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('inject_key', $field->inject_key, array(
			'class' => 'form-control', 'id' => 'inject_key', 'size' => 50, 'maxlength' => 50
		)); ?>
		
		<p class="help-block"><?php echo __('The key is used for injection into the related widget'); ?></p>
	</div>
</div>