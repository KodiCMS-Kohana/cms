<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="widget_id"><?php echo __('Widget ID'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('widget_id', $widget->widget_id, array(
				'class' => 'form-control', 'id' => 'widget_id'
			)); ?>
		</div>
	</div>
</div>