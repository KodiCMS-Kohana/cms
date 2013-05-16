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

cms.init.add('page_edit', function() {
	$(function() {
		reload_blocks();
		$('.widget-select-block').on('change', function() {
			reload_blocks();
		});
	});
	
	function reload_blocks() {
		var FILLED_BLOCKS = {};

		$('.widget-select-block').each(function() {
			var cb = $(this).val();
			
			if(!cb || cb == 0 || cb == 'PRE') return;
			FILLED_BLOCKS[cb] = LAYOUT_BLOCKS[cb];
		});
		
		$('.widget-select-block').each(function() {		
			var cb = $(this).val();
			$(this).empty();
			for(block in LAYOUT_BLOCKS) {
				if(!FILLED_BLOCKS[block] || (FILLED_BLOCKS[block] && block == cb) )
					$(this).append('<option value="'+ block +'">' + LAYOUT_BLOCKS[block] + '</option>');
			}
			
			$('option[value="'+cb+'"]', this).attr('selected', 'selected');
		});
	}
});