cms.init.add(['sheduler_index'], function () {
	var $fullcalendar = $('#calendar');

	var date = new Date(),
		dateYear = date.getFullYear(),
		dateMonth = date.getMonth(),
		dateDay = date.getDate();

	// build options
	var options = {
		header: {
			left: 'prev,next,today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: false,
		lazyFetching: false,
		disableResizing: true,
		year: dateYear,
		month: dateMonth,
		date: dateDay,
		allDaySlot: true,

		events: function(start, end, callback)
		{
			var roundedStart = Math.round(start.getTime() / 1000),
				roundedEnd = Math.round(end.getTime() / 1000);

			Api.get('sheduler', {from: roundedStart, to: roundedEnd}, function(response) {
				if( ! response.response) return;

				var events = [];
				for(i in response.response) {
					var item = response.response[i];
					item.className = 'popup fancybox.iframe';
					events.push(item);
				}
				callback(events);
			});


		}
	};

	$fullcalendar.fullCalendar(options);
});