var Dashboard = {
	widgets: {
		gridster: null,
		init: function() {
			this.gridster = $(".gridster ul").gridster({
				widget_base_dimensions: [150, 100],
				widget_margins: [5, 5],
				autogrow_cols: true,
				resize: {
					enabled: true,
					start: function (e, ui, $widget) {
						$widget.find('.dashboard-widget').trigger('resize_start', [this, ui]);
					},
					stop: function (e, ui, $widget) {
						Dashboard.widgets.save_order();
						$widget.find('.dashboard-widget').trigger('resize_stop', [this, ui]);
					}
				},
				draggable: {
					start: function (e, ui, $widget) {},
					drag: function (e, ui, $widget) {},
					stop: function (e, ui, $widget) {
						Dashboard.widgets.save_order();
						$('.gridster ul .preview-holder').remove();
					}
				},
				serialize_params: function($w, wgd) {
					return {
						col: wgd.col,
						row: wgd.row,
						sizex: wgd.size_x,
						sizey: wgd.size_y,
						'max-sizex': wgd.max_size_x,
						'max-sizey': wgd.max_size_y,
						'min-sizex': wgd.min_size_x,
						'min-sizey': wgd.min_size_y,
						widget_id: $w.data('widget_id')
					};
				}
			}).data('gridster');
		},
		add: function(html, id, size) {
			try {
				var widget = this.gridster.add_widget.apply(this.gridster, [$('<li />').append(html), size.x, size.y, false, false, size.max_size, size.min_size]);
				widget.data('widget_id', id);
				
				widget.trigger('widget_init', []);
			} catch (e) {
				console.log('Add widget error', e);
				return;
			}
	
			$.fancybox.close();
			Dashboard.widgets.save_order();
		},
		remove: function(btn) {
			var widget = btn.closest('li');
			
			Api.delete('dashboard.widget', {
				id: widget.data('widget_id')
			}, function(response) {
				Dashboard.widgets.gridster.remove_widget(widget, function() {
					Dashboard.widgets.save_order();
				});
			});
		},
		save_order: function(array) {
			Api.post('user-meta', {key: 'dashboard', value: this.gridster.serialize()});
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
			if(typeof response.media == 'object') {
				for (i in response.media) {
					getScript(response.media[i]);
				}
			}

			setTimeout(function(){
				Dashboard.widgets.add($(response.response), response.id, response.size);
			}, 500);
		});
	});
	
	$('body').on('click', '.dashboard-widget .remove_widget', function(e) {
		var $self = $(this);
		Dashboard.widgets.remove($self);
		e.preventDefault();
	});
	
	$('body').on('click', '.dashboard-widget .widget_settings', function(e) {
		var $cont = $(this).closest('.dashboard-widget');
		
		get_widget_settings($cont.data('id'));
		e.preventDefault();
	});
	
	$('body').on('submit', 'form.widget-settings', function(e) {
		var $self = $(this);
		var widget_id = $self.find('input[name="id"]').val();
		
		Api.post($self.attr('action'), $self.serialize(), function(response) {
			var $cont = $('.dashboard-widget[data-id="' + widget_id + '"]');
			$cont.replaceWith(response.response);
			
			if(response.update_settings)
				get_widget_settings(widget_id);
		});
		
		e.preventDefault();
	});
});

function get_widget_settings(widget_id) {
	Api.get('dashboard.widget', {
		id: widget_id
	}, function(response) {
		$.fancybox({
			fitToView	: true,
			autoSize	: false,
			width		: '99%',
			height		: '99%',
			content		: response.response
		});
	});
}

function getScript(url) {
	if($('script[src="' + url + '"]').length > 0)
		return;

	var script = document.createElement('script');
	script.type = "text/javascript";
	script.src = url;

	script.onreadystatechange = function () {
		if (script.readyState === "loaded" || script.readyState === "complete") {
			script.onreadystatechange = null;
		}
	};
	
	document.getElementsByTagName("head")[0].appendChild(script);
}