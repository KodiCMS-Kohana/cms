cms.init.add('widgets_edit', function() {
	$('#WidgetTemplate').change(function() {
		var $option = $('option:selected', this);
		if($option.val() == 0)
			$('#WidgetTemplateButton').hide();
		else
			$('#WidgetTemplateButton')
				.show()
				.attr('href', BASE_URL + '/snippet/edit/' + $option.val())
	});

	$('body').on('post:api:snippet, put:api:snippet', function(event, response) {
		var $option = $('<option selected value="'+response.name+'">'+response.name+'</oprion>');
		$('#WidgetTemplate')
			.find('option:selected')
				.removeAttr('selected')
			.end()
			.append($option)
			.change();
	});
	
	var cache_enabled = function() {
		
		var $caching_input = $('#caching');
		var $cache_lifetime = $('#cache_lifetime');
		
		$cache_lifetime.prop('disabled', !$caching_input.prop('checked'));
	}
	
	$('#caching').on('change', cache_enabled);
	cache_enabled();
});