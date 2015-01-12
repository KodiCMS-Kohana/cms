<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="param"><?php echo __('Param'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('param', $widget->param, array(
				'class' => 'form-control', 'id' => 'param'
			)); ?>
		</div>
	</div>
</div>