<div class="widget-content widget-no-border-radius">
	<div class="control-group">
		<label class="control-label" for="length"><?php echo __('Field options'); ?></label>
		<div class="controls">
			<?php echo Form::textarea('select', implode("\n", $field->select)); ?>
			
			<div class="help-block"><?php echo __('Set each new value appear in a new line'); ?></div>
		</div>
	</div>
	
</div>