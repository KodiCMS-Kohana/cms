var Dashboard = {
	widgets: {
		init: function() {
			$('.dashboard-widgets-column').sortable({
				placeholder: 'sortable-placeholder',
				connectWith: '.dashboard-widgets-column',
				items: '.dashboard-widget',
				handle: '.handle',
				cursor: 'move',
				distance: 2,
				tolerance: 'pointer',
				forcePlaceholderSize: true,
				helper: 'clone',
				opacity: 0.65,
				stop: function() {
					Dashboard.widgets.save_order();
				},
				receive: function(e,ui) {
					Dashboard.widgets._mark_area();
				}
			});

			Dashboard.widgets._mark_area();
		},
		save_order: function() {
			var data = {};

			$('.dashboard-widgets-column').each(function() {
				data[$(this).data('column')] = $(this).sortable('toArray', {
					attribute: 'data-id'
				});
			});
			
			Api.post('user-meta', {key: 'dashboard', value: data});
		},
		_mark_area: function() {
			var visible = $('div.dashboard-widget:visible').length;

			$('.dashboard-widgets-column:visible').each(function() {
				var t = $(this);

				if (visible == 1 || t.children('.dashboard-widget:visible').length)
					t.removeClass('empty-container');
				else
					t.addClass('empty-container');
			});
		}
	}
};

cms.init.add('dashboard_index', function () {
	Dashboard.widgets.init();
	
	$('#add-widget').on('click', function(e) {
		e.preventDefault();
	});
	
	$('body').on('click', '.popup-btn', function() {
		var widget_type = $(this).data('type');
		Api.put('dashboard.widget', {
			widget_type: widget_type
		}, function(response) {
			$('#dashboard-widgets .dashboard-widgets-column[data-column="left"]').append($(response.response));
			$.fancybox.close();
			Dashboard.widgets._mark_area();
			Dashboard.widgets.save_order();
		});
	});
	
	$('body').on('click', '.dashboard-widget .remove_widget', function(e) {
		var $cont = $(this).closest('.dashboard-widget');

		Api.delete('dashboard.widget', {
			widget_id: $cont.data('id')
		}, function(response) {
			$cont.remove();
			Dashboard.widgets._mark_area();
			Dashboard.widgets.save_order();
		});
		e.preventDefault();
	});
	
	$('body').on('click', '.dashboard-widget .widget_settings', function(e) {
		var $cont = $(this).closest('.dashboard-widget');

		Api.get('dashboard.widget', {
			widget_id: $cont.data('id')
		}, function(response) {
			$.fancybox({
				fitToView	: true,
				autoSize	: false,
				width		: '99%',
				height		: '99%',
				content		: response.response
			});
		});

		e.preventDefault();
	});
	
	$('body').on('submit', 'form.widget-settings', function(e) {
		var $self = $(this);
		var widget_id = $self.find('input[name="widget_id"]').val();
		
		Api.post($self.attr('action'), $self.serialize(), function(response) {
			var $cont = $('.dashboard-widget[data-id="' + widget_id + '"]');
			$cont.replaceWith(response.response);
			$.fancybox.close();
		});
		
		e.preventDefault();
	});
});