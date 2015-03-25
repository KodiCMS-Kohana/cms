<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Widget'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('related_widget_id',  $select, $widget->related_widget_id, array('id' => 'related_widget_id')); ?>	
		</div>
	</div>
	
	<hr class="panel-wide"/>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="query_key"><?php echo __('Query key (GET)'); ?></label>
		<div class="col-md-2">
			<?php echo Form::input('query_key',  $widget->get('query_key'), array('id' => 'query_key', 'class' => 'form-control')); ?>	
		</div>
	</div>
</div>