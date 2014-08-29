<div class="panel dashboard-widget fullcalendar-widget" data-id="<?php echo $widget->id; ?>">
	<div class="panel-heading handle">
		<span class="panel-title" data-icon="calendar"><?php echo __('Calendar'); ?>&nbsp;</span>
		
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-default btn-xs remove_widget"><?php echo UI::icon('times'); ?></button>
		</div>
	</div>
	<div id="calendar" class="panel-body padding-sm"></div>
</div>
<link type="text/css" href="/cms/media/libs/fullcalendar-2.1.0/fullcalendar.min.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="/cms/media/libs/fullcalendar-2.1.0/fullcalendar.min.js"></script>
<script type="text/javascript" src="/cms/media/libs/fullcalendar-2.1.0/lang/ru.js"></script>
<script type="text/javascript">
$(function(){
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next,today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
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
});
</script>