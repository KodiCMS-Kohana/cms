<div class="panel dashboard-widget" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title"><?php empty($header) ? '' : $header; ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs widget_settings"><?php echo UI::icon('cog'); ?></button>
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>

	<div class="panel-body">
		Widget data
	</div>
</div>