<!--
Прмиеры верстки виджетов можно посмотреть здесь:
http://infinite-woodland-5276.herokuapp.com/stat-panels.html
http://infinite-woodland-5276.herokuapp.com/widgets.html
http://infinite-woodland-5276.herokuapp.com/index.html

-->
<div class="panel dashboard-widget">
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