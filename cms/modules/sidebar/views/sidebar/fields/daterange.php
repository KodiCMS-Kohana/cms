<div class="form-group form-group-sm">
	<?php echo Form::label(NULL, $label, array('class' => 'control-label')); ?>
	<div class="form-inline">
		<div class="input-group">
			<?php echo implode('<div class="input-group-addon">-</div>', $range); ?>
		</div>
	</div>
</div>