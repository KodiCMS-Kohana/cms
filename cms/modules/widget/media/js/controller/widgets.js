cms.init.add('widgets_edit', function() {
	
	var cache_enabled = function() {
		var $caching_input = $('#caching');
		var $cache_lifetime = $('#cache_lifetime');
		
		$cache_lifetime.prop('disabled', !$caching_input.prop('checked'));
		
		if($caching_input.prop('checked'))
			$('#cache_settings_container').show();
		else
			$('#cache_settings_container').hide();
		
		higlight_cache_time();
	};
	
	$('#caching').on('change', cache_enabled).change();
	
	$('#cache_lifetime').on('keyup', function() {
		higlight_cache_time();
	});
	
	function higlight_cache_time() {
		$('#cache_lifetime_labels .label')
			.each(function() {
				if($('#cache_lifetime').val() == $(this).data('value'))
					$(this).addClass('label-success');
			});
	};
});

cms.init.add('page_edit', function() {
	var layout_file = PAGE_OBJECT['layout'];
	reload_blocks(layout_file);	
	$('body').on('post:backend:api-layout.rebuild', function(e, response) {
		reload_blocks(layout_file);
	});
	
	// Reload blocks on page layout change
//	$('body').on('change', '#page_layout_file', function() {
//		$('.widget-blocks').data('layout', $(this).val());
//		reload_blocks($(this).val());
//	});
	
	$('body').on('click', '.popup-widget-item', function() {
		var widget_id = $(this).data('id');
		
		Api.put('widget', {
			widget_id: widget_id,
			page_id: PAGE_ID
		}, function(response) {
			window.location = '#widgets';
			$.fancybox.close();
			$('#widget-list tbody').append(response.response);
			reload_blocks(layout_file);
		});
	});
});

cms.init.add('widgets_location', function() {
	reload_blocks();
	
	$('body').on('post:backend:api-layout.rebuild', function(e, response) {
		reload_blocks();
	});
	
	$('#select_for_all').on('click', function(){
		var value = $('input[name="select_for_all"]').val();
		if(!value.length) return false;

		$('input.widget-blocks').each(function() {
			var $options = $(this).data('blocks');
			for(i in $options) {
				var $option = $options[i];
				if($option['id'].indexOf(value) > -1 || $option['text'].indexOf(value) > -1)
					$(this).select2("data", $option);
			}
		});

		return false;
	});

	$('.set_to_inner_pages').on('click', function() {
		var cont = $(this).closest('tr');

		var block_name = cont.find('.widget-blocks').select2("data")['id'];
		var position = cont.find('input.widget-position').val();
		var id = cont.data('id');
		
		$('.table tbody tr[data-parent-id="'+id+'"]').each(function() {
			var $select = $(this).find('input.widget-blocks');
			var $options = $select.data('blocks');
			for(i in $options) {
				var $option = $options[i];
				if($option['id'].indexOf(block_name) > -1)
					$select.select2("data", $option);
			}
		
			$(this).find('input.widget-position').val(position);
		});
		return false;
	});
});

cms.init.add('widgets_template', function() {
	$('#highlight_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('highlight_content', 'changeHeight', cms.content_height);
	});
	
	$(window).resize(function() {
		$('#highlight_content').trigger('filter:switch:on');
	});
});

function format_dropdown_block(state, container) {
	if (!state.id) return state.text; // optgroup
	
	if(state.id == -1 || state.id == 0 || state.id == 'PRE' || state.id == 'POST') {
		container.css({'color': 'blue', 'fontWeight': 'bold'});
	} else {
		container.css({'color': 'green', 'fontWeight': 'bold'});
	}
	
	return state.text;
}

function reload_blocks($layout) {
	var FILLED_BLOCKS = [];
	var LAYOUT_BLOCKS = {};
	var $layout_data = {};
	if($layout) {
		$layout_data = {layout: $layout};
	}

	Api.get('layout.blocks', $layout_data, function(resp) {
		if( ! resp.response) return;
		LAYOUT_BLOCKS = resp.response;

		$('.widget-blocks').each(function() {
			var cb = $(this).val();
			var $layout = $(this).data('layout');
			if( ! LAYOUT_BLOCKS[$layout]) return;
			
			var blocks = [];
			for(block in LAYOUT_BLOCKS[$layout]) {
				if(!FILLED_BLOCKS[block] || (FILLED_BLOCKS[block] && block == cb) )
					blocks.push({id: block, text: LAYOUT_BLOCKS[$layout][block]});
			}

			found = false;
			for(i in blocks) {
				if(blocks[i].id == cb)
					found = true;
			}

			if(!found) {
				cb = -1;
			}

			$(this)
				.select2({
					data: blocks,
					formatSelection: format_dropdown_block,
					formatResult: format_dropdown_block
				})
				.select2('val', cb)
				.data('blocks', blocks);
		});
	});
}