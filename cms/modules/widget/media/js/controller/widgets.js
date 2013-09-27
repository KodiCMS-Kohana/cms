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

	$('body').on('post:api-snippet', update_snippets_list);
	$('body').on('put:api-snippet', update_snippets_list);
	
	function update_snippets_list(e, response) {
		var select = $('#WidgetTemplate');

		select
			.append($('<option>', {value: response.name, text: response.name}))
			.select2('val', response.name)
			.change();
	}
	
	var cache_enabled = function() {
		var $caching_input = $('#caching');
		var $cache_lifetime = $('#cache_lifetime');
		
		$cache_lifetime.prop('disabled', !$caching_input.prop('checked'));
	}
	
	$('#caching').on('change', cache_enabled).change();
	
	$('.cache-time-label').on('click', function() {
		$('#cache_lifetime').val($(this).data('time'));
		 higlight_cache_time();
	});
	
	$('#cache_lifetime').on('keyup', function() {
		higlight_cache_time();		
	});
	
	function higlight_cache_time() {
		$('.cache-time-label').removeClass('label-success');
		$('.cache-time-label').each(function() {
			if($('#cache_lifetime').val() == $(this).data('time'))
				$(this).addClass('label-success');
		})
		
		 $('#caching').check();
		 cache_enabled();
	};
});

cms.init.add('page_edit', function() {
	reload_blocks();
	$('.widget-select-block').on('change', function() {
		reload_blocks();
	});
	
	$('body').on('click', '.popup-widget-item', function() {
		var widget_id = $(this).data('id');
		$.get('/ajax-widget-add', {
			widget_id: widget_id,
			page_id: PAGE_ID
		}, function(response) {
			window.location = '#widgets';
			
			$.fancybox.close();
			
			$('#widget-list tbody').append(response.widget);
			
			reload_blocks();
		}, 'json');
	})
	
	function reload_blocks() {
		var FILLED_BLOCKS = [];

		$('.widget-select-block').each(function() {
			var cb = $(this).val();
			
			if(!cb || cb == 0 || cb == 'PRE') return;

			FILLED_BLOCKS[cb] = LAYOUT_BLOCKS[cb];
		});
		
		
		$('.widget-select-block').each(function() {		
			var cb = $(this).val();
			var blocks = [];
			for(block in LAYOUT_BLOCKS) {
				if(!FILLED_BLOCKS[block] || (FILLED_BLOCKS[block] && block == cb) )
					blocks.push({id: block, text: LAYOUT_BLOCKS[block]});
			}
		
			$(this).select2({
				data: blocks
			}).select2('val', cb);
		});
	}
});