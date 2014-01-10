<div class="widget-content widget-no-border-radius">
	<div class="control-group">
		<label class="control-label" for="allow_html"><?php echo __('Allow HTML tags'); ?></label>
		<div class="controls">
			<?php echo Form::checkbox('allow_html', 1, $field->allow_html == 1, array('id' => 'allow_html' )); ?>
		</div>
	</div>
</div>