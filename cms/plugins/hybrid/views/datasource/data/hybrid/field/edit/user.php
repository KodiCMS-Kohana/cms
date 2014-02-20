<div class="widget-content ">
	<div class="control-group">
		<label class="control-label" for="only_current"><?php echo __('Set current user'); ?></label>
		<div class="controls">
			<?php echo Form::checkbox('only_current', 1, $field->only_current == 1, array('id' => 'only_current' )); ?>
		</div>
	</div>
</div>