<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if( ! $widget->ds_id ): ?>
<div class="note note-warning">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="panel-body">
	<div class="form-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('auto_publish', 1, $widget->auto_publish); ?> <?php echo __('Set document published'); ?></label>
			<label class="checkbox"><?php echo Form::checkbox('disable_update', 1, $widget->disable_update); ?> <?php echo __('Disable update existing documents'); ?></label>
		</div>
	</div>
	
	<div class="well">
		<div class="form-group">
			<label class="control-label col-md-3" for="data_source"><?php echo __('Hybrid data source')?></label>
			<div class="controls">
				<?php echo Form::select('data_source', $widget->src_types()); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3" for="data_source_prefix"><?php echo __('Hybrid data source prefix')?></label>
			<div class="controls">
				<?php echo Form::input('data_source_prefix', $widget->data_source_prefix); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="redirect_url"><?php echo __('Redirect url on success')?></label>
		<div class="controls">
			<?php echo Form::input('redirect_url', $widget->redirect_url); ?>
		</div>
	</div>
</div>
<?php endif; ?>