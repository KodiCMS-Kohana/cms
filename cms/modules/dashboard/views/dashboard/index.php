<div class="page-header">
	<div class="row">
		<div class="col-xs-6">
			<h1 data-icon="dashboard"><?php echo __('Dashboard'); ?></h1>
		</div>
		
		<div class="col-xs-6 text-right">
			<a class="btn btn-primary btn-labeled fancybox.ajax popup" href="/api-dashboard.widget_list/" id="add-widget">
				<span class="btn-label icon fa fa-plus"></span><?php echo __('Add widget'); ?>
			</a>
		</div>
	</div>
</div>

<div id="dashboard-widgets" class="row">
	<?php foreach ($columns as $side => $class): ?>
	<div class="<?php echo $class; ?>">
		<div class="dashboard-widgets-column" data-column="<?php echo $side; ?>">
			<?php foreach (Arr::get($widgets, $side, array()) as $widget) echo $widget->run(); ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>