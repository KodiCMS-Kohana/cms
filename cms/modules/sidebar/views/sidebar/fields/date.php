<?php if ($inline === TRUE): ?>
	<?php echo Form::input($name, $value, $attributes); ?>
<?php else: ?>
<div class="form-group">
	<?php if (!empty($label)): ?>
    <?php echo Form::label($attributes['id'], $label, array('class' => 'control-label')); ?>
	<?php endif; ?>
	<br />
	<?php echo Form::input($name, $value, $attributes); ?>
</div>
<?php endif; ?>
