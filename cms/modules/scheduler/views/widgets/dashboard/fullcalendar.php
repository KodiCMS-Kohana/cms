<div class="panel dashboard-widget panel-body-colorful panel-info fullcalendar-widget">
	<button type="button" class="close remove_widget"><?php echo UI::icon('times'); ?></button>
	<div id="calendar" class="panel-body" style="height: 100%"></div>
</div>
<link type="text/css" href="/cms/media/libs/fullcalendar-2.1.0/fullcalendar.min.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="/cms/media/libs/fullcalendar-2.1.0/fullcalendar.min.js"></script>
<script type="text/javascript" src="/cms/media/libs/fullcalendar-2.1.0/lang/ru.js"></script>
<script type="text/javascript">
$('.fullcalendar-widget')
	.on('widget_init', function(e, w ,h) {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next,today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			contentHeight: h - 100,
			editable: true,
			eventRender: function(event, element) {
				var content = element.find('.fc-content');
				if (event.icon) {
					content.prepend("<i class='fa-icon fa fa-" + event.icon + "'></i>");
				}

				$("<span class='btn-close'><i class='fa fa-times'></i></span>")
					.appendTo(content)
					.on('click', function() {
						if(event.id)
							Api.delete('calendar', {id: event.id}, function() {
								$('#calendar').fullCalendar('removeEvents', event.id );
							});
						else
							$('#calendar').fullCalendar('removeEvents', event.id );
					});
			},
			events: {
				url: 'api-calendar',
				success: function(response) {
					var events = response.response;

					for(i in events) {
						events[i]['allDay'] = events[i]['allDay'] == 1;
					}
					return events;
				}
			}
		});
	})
	.on('resize_stop', function(e, gridster, ui, w, h) {
		$('#calendar').fullCalendar('option', 'contentHeight', h - 100);
	});
</script>