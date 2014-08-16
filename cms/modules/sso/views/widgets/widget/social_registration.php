<div class="widget-header">
	<h4><?php echo __('Social providers'); ?></h4>
</div>
<div class="panel-body">
	<?php foreach($widget->get_registered_providers() as $provider => $data): ?>
	<div class="form-group">
		<label class="checkbox">
			<?php echo Form::checkbox('providers['.$provider.']', 1, !empty($widget->providers[$provider])); ?>
			<?php echo $widget->get_provider_param($provider, 'name'); ?>
		</label>
		<div class="clearfix"></div>
		<ul class="unstyled">
			<?php foreach($data as $key => $value): ?>
				<li><strong><?php echo $key; ?></strong>: <?php echo $value; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endforeach; ?>
</div>