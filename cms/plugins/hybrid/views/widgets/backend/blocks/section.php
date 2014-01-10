<div class="widget-header">
	<h4><?php echo __('Data'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="ds_id"><?php echo __('Section'); ?></label>
		<div class="controls">
			<?php 
			echo Form::select( 'ds_id', $widget->options(), $widget->ds_id, array(
				'class' => 'input-large', 'id' => 'ds_id'
			) );
			?>
		</div>
	</div>

	<?php /*
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('only_sub', 1, $widget->only_sub); ?> <?php echo __('Use subsections only'); ?></label>
		</div>
	</div>
	 * 
	 */
	?>
</div>