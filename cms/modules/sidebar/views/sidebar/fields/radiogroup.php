<div class="form-group form-inline">
	<?php if (!empty($label)): ?>
    <?php echo Form::label('', $label, array('class' => 'control-label')); ?>
	<?php endif; ?>
	<?php foreach ($options as $option): ?>
		<?php echo $option; ?>
	<?php endforeach; ?>
</div>