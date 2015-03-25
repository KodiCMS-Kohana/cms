<div class="page-header">
	<div class="row">
		<div class="col-xs-6">
			<h1 data-icon="dashboard"><?php echo __('Dashboard'); ?></h1>
		</div>
		
		<div class="col-xs-6 text-right">
			<a class="btn btn-primary btn-labeled fancybox.ajax popup" href="<?php echo URL::site('api-dashboard.widget_list'); ?>" id="add-widget">
				<span class="btn-label icon fa fa-cubes"></span><?php echo __('Add widget'); ?>
			</a>
		</div>
	</div>
</div>

<div id="dashboard-widgets">
	<div class="gridster">
		<ul class="list-unstyled">
			<?php foreach ($widgets as $data): ?>
			<li <?php foreach ($data as $key => $v): if($key == 'widget') continue; ?>data-<?php echo $key; ?>="<?php echo $v; ?>"<?php endforeach; ?>>
				<?php echo $data['widget']->run(); ?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>