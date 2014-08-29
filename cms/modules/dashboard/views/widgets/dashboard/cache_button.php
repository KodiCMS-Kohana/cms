<div class="panel dashboard-widget panel-warning panel-body-colorful panel-dark" data-id="<?php echo $widget->id; ?>">
	<div class="panel-body text-center handle">
		<button type="button" class="close remove_widget"><?php echo UI::icon('times'); ?></button>

		<?php echo UI::button(__('Clear cache'), array(
			'icon' => UI::icon('trash-o fa-lg'),
			'class' => 'btn-lg btn-success btn-flat btn-block',
			'data-api-url' => 'cache',
			'data-method' => Request::DELETE
		)); ?>
	</div>
</div>