<div class="form-group">
	<?php if (!empty($label)): ?>
	<?php echo Form::label($attributes['id'], $label, array('class' => 'control-label')); ?>
	<?php endif; ?>
	<?php echo Form::file($name, $attributes); ?>
</div>