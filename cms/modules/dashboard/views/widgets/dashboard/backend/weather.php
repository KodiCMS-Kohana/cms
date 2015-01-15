<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="city"><?php echo __('City'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('city', $widget->city, array(
				'class' => 'form-control', 'id' => 'city'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="color"><?php echo __('Color'); ?></label>
		<div class="col-md-9">
			<?php 
			$colors = array(
				'transparent' => __('White'), 
				'info' => __('Blue'),
				'success' => __('Green'), 
				'warning' => __('Orange'), 
				'danger' => __('Red')
			);
			echo Form::select('color', $colors, $widget->color, array(
				'class' => 'form-control', 'id' => 'color'
			)); ?>
		</div>
	</div>
</div>