<?php if($inline === TRUE): ?>
<div class="radio-inline">
	<label>
		<?php echo Form::radio($name, $value, $selected, $attributes); ?> <?php if ( !empty($label)): ?><?php echo $label; ?><?php endif; ?>
	</label>
</div>
<?php else: ?>
<div class="form-group">
	<div class="radio">
		<label>
			<?php echo Form::radio($name, $value, $selected, $attributes); ?> <?php if ( !empty($label)): ?><?php echo $label; ?><?php endif; ?>
		</label>
	</div>
</div>
<?php endif; ?>
