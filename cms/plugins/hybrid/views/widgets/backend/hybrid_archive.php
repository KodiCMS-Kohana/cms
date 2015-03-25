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
		<label class="control-label col-md-3" for="date_field"><?php echo __('Field')?></label>
		<div class="col-md-3">
			<?php echo Form::select('date_field', $widget->get_date_fields(),  $widget->date_field); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="order_by"><?php echo __('Order by'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('order_by', array(
					'asc' => __('ASC'),
					'desc' => __('DESC'),
				), $widget->order_by); ?>	
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="archive_type"><?php echo __('Type'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('archive_type', array(
					'month' => __('Archive by month'),
					'year' => __('Archive by year'),
					'day' => __('Archive by day')
				), $widget->archive_type); ?>	
		</div>
	</div>
</div>
<?php endif; ?>