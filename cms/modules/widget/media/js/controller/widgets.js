cms.init.add('widgets_edit', function() {
	$('#WidgetTemplate').change(function() {
		var $option = $('option:selected', this);
		if($option.val() == 0)
			$('#WidgetTemplateButton').hide();
		else
			$('#WidgetTemplateButton')
				.show()
				.css({
					display: 'inline-block'
				})
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
		
		if($caching_input.prop('checked'))
			$('#cache_settings_container').show();
		else
			$('#cache_settings_container').hide();
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

cms.init.add('widgets_template', function() {
	function calculateEditorHeight() {
		var conentH = cms.content_height;
		var h = $('.widget-title').outerHeight(true) + $('.widget-header').outerHeight(true) + $('.form-actions').outerHeight(true) + 10;
		return conentH - h;
	}

	$('#highlight_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('highlight_content', 'changeHeight', calculateEditorHeight());
	});

	$(window).resize(function() {
		$('#highlight_content').trigger('filter:switch:on')
	});
})

cms.init.add('page_edit', function() {
	reload_blocks();
	$('.widget-select-block').on('change', function() {
		reload_blocks();
	});
	
	$('body').on('click', '.popup-widget-item', function() {
		var widget_id = $(this).data('id');
		
		Api.put('/api-widget', {
			widget_id: widget_id,
			page_id: PAGE_ID
		}, function(response) {
			window.location = '#widgets';
			$.fancybox.close();
			$('#widget-list tbody').append(response.response);
			reload_blocks();
		});
	})
	
	function reload_blocks() {
		var FILLED_BLOCKS = [];

		$('.widget-select-block').each(function() {
			var cb = $(this).val();
			
			if(!cb || cb == 0 || cb == 'PRE') return;

			//FILLED_BLOCKS[cb] = LAYOUT_BLOCKS[cb];
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

cms.init.add('widgets_location', function() {
	$('#select_for_all').click(function(){
		var value = $('input[name="select_for_all"]').val();
		
		if(!value.length) return false;

		$('select.blocks').each(function() {
			if(val = $(this).find('option[value*="'+value+'"]').val())
				$(this).select2("val", val);
		});

		return false;
	});
	
	$('.set_to_inner_pages').on('click', function() {
		var cont = $(this).parent().parent();

		var block_name = cont.find('select').val();
		var position = cont.find('input.widget-position').val();
		var id = cont.data('id');
		
		$('.table tbody tr[data-parent-id="'+id+'"]').each(function() {
			if(val = $(this).find('option[value*="'+block_name+'"]').val()) {
				$(this).find('select').select2("val", val);
			}
		
			$(this).find('input.widget-position').val(position);
		});
		return false;
	});
});