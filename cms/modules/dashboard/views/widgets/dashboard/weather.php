<div class="panel dashboard-widget panel-<?php echo $widget->get('color', 'success'); ?> panel-body-colorful panel-dark" data-id="<?php echo $widget->id; ?>">
	<button type="button" class="close remove_widget"><?php echo UI::icon('times'); ?></button>
	<button type="button" class="close widget_settings"><?php echo UI::icon('cog'); ?></button>
	<div class="panel-body text-center handle"></div>
</div>
<script type="text/javascript">
$('.dashboard-widget[data-id="<?php echo $widget->id; ?>"]')
	.on('widget_init', function() {
		var $cont = $('.panel-body', this);
		$.simpleWeather({
			location: '<?php echo $widget->city; ?>',
			woeid: '',
			unit: 'c',
			success: function(weather) {
				html = '<h1><i class="fa fa-lg wth wth-'+weather.code+'"></i> '+weather.city+' '+weather.temp+'&deg;'+weather.units.temp+'</h1>';
				$cont.html(html);
			},
			error: function (error) {
				$cont.html('<h4>'+error.message+'</h4>');
			}
		});
	});
</script>