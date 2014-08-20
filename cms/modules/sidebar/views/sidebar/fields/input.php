<div class="form-group form-inline">
	<?php if (!empty($label)): ?>
    <?php echo Form::label($attributes['id'], $label, array('class' => 'control-label')); ?>
	<?php endif; ?>
	<br />
	<?php echo Form::input($name, $value, $attributes); ?>
</div>