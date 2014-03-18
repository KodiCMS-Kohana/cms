<div class="widget-content ">
	<div class="control-group">
		<label class="control-label" for="length"><?php echo __('Field options'); ?></label>
		<div class="controls">
			<?php echo Form::textarea('options', implode("\n", $field->options)); ?>
			
			<div class="help-block"><?php echo __('Set each new value appear in a new line'); ?></div>
		</div>
	</div>
	
</div>