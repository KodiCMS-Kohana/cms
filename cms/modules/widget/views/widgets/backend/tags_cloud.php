<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Min font-size (px)'); ?></label>
		<div class="col-md-2">
			<div class="input-group">
				<?php echo Form::input('min_size', $widget->min_size, array('class' => 'form-control')); ?>
				<div class="input-group-addon">px</div>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Max font-size (px)'); ?></label>
		<div class="col-md-2">
			<div class="input-group">
				<?php echo Form::input('max_size', $widget->max_size, array('class' => 'form-control')); ?>
				<div class="input-group-addon">px</div>
			</div>
		</div>
	</div>
	
	<hr />

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Order by'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('order_by', array(
					'name_asc' => __('Tag name A-Z'),
					'name_desc' => __('Tag name Z-A'),
					'count_asc' => __('Count tags 0-9'),
					'count_desc' => __('Count tags 9-0'),
				), $widget->order_by); ?>	
		</div>
	</div>
</div>