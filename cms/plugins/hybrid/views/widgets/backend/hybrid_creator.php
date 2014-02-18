<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<div class="widget-header">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('auto_publish', 1, $widget->auto_publish); ?> <?php echo __('Set document published'); ?></label>
		</div>
	</div>
	
	<div class="well">
		<div class="control-group">
			<label class="control-label" for="data_source"><?php echo __('Hybrid data source')?></label>
			<div class="controls">
				<?php echo Form::select('data_source', $widget->src_types()); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="data_source_prefix"><?php echo __('Hybrid data source prefix')?></label>
			<div class="controls">
				<?php echo Form::input('data_source_prefix', $widget->data_source_prefix); ?>
			</div>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="redirect_url"><?php echo __('Redirect url on success')?></label>
		<div class="controls">
			<?php echo Form::input('redirect_url', $widget->redirect_url); ?>
		</div>
	</div>
</div>


