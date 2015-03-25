<div class="panel dashboard-widget reformal-widget panel-info" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title" data-icon="lightbulb-o fa-lg"><?php echo $header; ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs widget_settings"><?php echo UI::icon('cog'); ?></button>
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<?php if ($widget->widget_id === NULL): ?>
	<div class="note note-warning">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need set Reformal widget ID'); ?>
	</div>
	<?php else: ?>
	<iframe style="width: 100%; height: <?php echo $widget->height; ?>px; border: 0;" frameborder="0" src="http://reformal.ru/widget/<?php echo $widget->widget_id; ?>"></iframe>
	<?php endif; ?>
</div>