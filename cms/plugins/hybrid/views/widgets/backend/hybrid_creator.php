<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if( ! $widget->ds_id ): ?>
<div class="widget-content">
	<div class="alert alert-warning">
		<i class="icon icon-lightbulb"></i> <?php echo __('You need select hybrid section'); ?>
	</div>
</div>
<?php else: ?>
<div class="widget-header">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('auto_publish', 1, $widget->auto_publish); ?> <?php echo __('Set document published'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('disable_update', 1, $widget->disable_update); ?> <?php echo __('Disable update existing documents'); ?></label>
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
<?php endif; ?>