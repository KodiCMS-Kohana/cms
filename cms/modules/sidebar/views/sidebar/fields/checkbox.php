<div class="checkbox">
	<label>
		<?php echo Form::checkbox($name, $value, $checked, $attributes); ?> <?php if ( !empty($label)): ?><?php echo $label; ?><?php endif; ?>
	</label>
</div>