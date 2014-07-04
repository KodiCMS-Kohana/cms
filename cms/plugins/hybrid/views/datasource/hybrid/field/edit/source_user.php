<div class="control-group">
	<div class="controls">
		<label class="checkbox"><?php echo Form::checkbox('only_current', 1, $field->only_current == 1, array('id' => 'only_current' )); ?> <?php echo __('Only current user'); ?></label>
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<label class="checkbox"><?php echo Form::checkbox('set_current', 1, $field->set_current == 1, array('id' => 'set_current' )); ?> <?php echo __('Set current user on create document'); ?></label>
	</div>
</div>