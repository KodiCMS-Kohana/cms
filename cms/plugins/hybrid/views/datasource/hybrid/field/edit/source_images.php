<div class="control-group">
	<label class="control-label"><?php echo __( 'Max file size' ); ?></label>
	<div class="controls">
		<?php echo Form::input('max_size', $field->max_size, array('class' => 'input-small', 'id' => 'max_size')); ?> (<?php echo Text::bytes($field->max_size); ?>)
		<span class="flags">
			<span class="label" data-value="<?php echo NUM::bytes('100K'); ?>">100k</span>
			<span class="label" data-value="<?php echo NUM::bytes('1MiB'); ?>">1Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('5MiB'); ?>">5Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('10MiB'); ?>">10Mib</span>
			<span class="label" data-value="<?php echo NUM::bytes('100MiB'); ?>">100Mib</span>
		</span>
	</div>
</div>