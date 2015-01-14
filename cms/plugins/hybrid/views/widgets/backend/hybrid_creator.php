<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if (!$widget->ds_id): ?>
<div class="alert alert-warning alert-dark">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="panel-body">
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('auto_publish', 1, $widget->auto_publish); ?> <?php echo __('Set document published'); ?></label>
				<br />
				<label><?php echo Form::checkbox('disable_update', 1, $widget->disable_update); ?> <?php echo __('Disable update existing documents'); ?></label>
			</div>
		</div>
	</div>
	
	<div class="well">
		<div class="form-group">
			<label class="control-label col-md-3" for="data_source"><?php echo __('Hybrid data source')?></label>
			<div class="col-md-3">
				<?php echo Form::select('data_source', $widget->src_types()); ?>
			</div>
		</div>
		<div class="form-group form-inline">
			<label class="control-label col-md-3" for="data_source_prefix"><?php echo __('Hybrid data source prefix')?></label>
			<div class="col-md-9">
				<?php echo Form::input('data_source_prefix', $widget->data_source_prefix, array('class' => 'form-control')); ?>
			</div>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="redirect_url"><?php echo __('Redirect url on success')?></label>
		<div class="col-md-9">
			<?php echo Form::input('redirect_url', $widget->redirect_url, array('class' => 'form-control')); ?>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/backend/blocks/fields', array(
	'widget' => $widget,
	'header' => __('Update only fields'),
	'fetch_widgets' => FALSE
)); ?>
<?php endif; ?>