// Skip errors when no access to console
var console = console || {log:function(){}};


// Main object
var cms = {};

cms.translations = {};

cms.addTranslation = function(obj)
{	
	for (var i in obj)
	{
		cms.translations[i] = obj[i];
	}
};

var __ = function(str)
{
	if (cms.translations[str] !== undefined)
		return cms.translations[str];
	else
		return str;
};


// Error
cms.error = function(msg, e)
{
	if (console != undefined)
		console.log(msg, e);
};


// Convert slug
cms.convert_dict = {'ą':'a','ä':'a','č':'c','ę':'e','ė':'e','i':'i','į':'i','š':'s','ū':'u','ų':'u','ü':'u','ž':'z','ö':'o'};

cms.convertSlug = function(str)
{
	return str.toString().toLowerCase()
			.replace(/[àâ]/g,   'a' )
			.replace(/[éèêë]/g, 'e' )
			.replace(/[îï]/g,   'i' )
			.replace(/[ô]/g,    'o' )
			.replace(/[ùû]/g,   'u' )
			.replace(/[ñ]/g,    'n' )
			.replace(/[äæ]/g,   'ae')
			.replace(/[öø]/g,   'oe')
			.replace(/[ü]/g,    'ue')
			.replace(/[ß]/g,    'ss')
			.replace(/[å]/g,    'aa')
			.replace(/(.)/g,    function(c){ return (cms.convert_dict[c] != undefined ? cms.convert_dict[c] : c); })
			.replace(/[^a-zа-яіїє0-9\.\_]/g, '-')
			.replace(/ /g,      '-')
			.replace(/\-{2,}/g, '-')
			.replace(/^-/,      '' );
			//.replace(/-$/,           '' ); // removed becouse this function used in #pageEditMetaSlugField
};


// cssZebra
cms.cssZebraItems = function(selector)
{
	$(selector).each(function(i){
		$(this)
			.removeClass('item-odd')
			.removeClass('item-even')
			.addClass(((i%2) == 0 ? 'item-odd' : 'item-even'));
	});
};


// Loader
cms.loader = {};

cms.loader.init = function()
{
	$('body').append('<div id="loader" class="loader"><span>'+ __('Loading') +'</span></div>');
};
			
cms.loader.show = function()
{
	$('#loader')
		.show()
		.animate({
			opacity:1
		}, 300);
};

cms.loader.hide = function()
{
	$('#loader')
		.animate({
			opacity:0
		}, 300, function(){ $(this).hide(); });
};


// Plugins
cms.plugins = {};


// Messages
cms.messages = {};

cms.messages.init = function()
{
	$('.message').animate({top: 0}, 1000);
};


// Filters
cms.filters = {};

// Filters array
cms.filters.filters = [];
cms.filters.switchedOn = {};

// Add new filter
cms.filters.add = function( name, to_editor_callback, to_textarea_callback )
{	
	if( to_editor_callback == undefined || to_textarea_callback == undefined )
	{
		frog.error('System try to add filter without required callbacks.', name, to_editor_callback, to_textarea_callback);
		return;
	}
	
	this.filters.push([ name, to_editor_callback, to_textarea_callback ]);
};

// Switch On filter
cms.filters.switchOn = function( textarea_id, filter )
{
	// Hack for rich text editors like TinyMCE
	jQuery( '#' + textarea_id ).css( 'display', 'block' );
	
	if( this.filters.length > 0 )
	{
		// Switch off previouse editor with textarea_id
		cms.filters.switchOff( textarea_id );
		
		for( var i=0; i<this.filters.length; i++ )
		{
			if( this.filters[i][0] == filter )
			{
				try
				{
					// Call handler that will switch on editor
					this.filters[i][1]( textarea_id );
					
					// Add editor to switchedOn stack
					cms.filters.switchedOn[textarea_id] = this.filters[i];
				}
				catch(e)
				{
					//frog.error('Errors with filter switch on!', e);
				}
				
				break;
			}
		}
	}
};

// Switch Off filter
cms.filters.switchOff = function( textarea_id )
{
	for( var key in cms.filters.switchedOn )
	{
		// if textarea_id param is set we search only one editor and switch off it
		if( textarea_id != undefined && key != textarea_id )
			continue;
		else
			textarea_id = key;
		
		try
		{
			if( cms.filters.switchedOn[key] != undefined && cms.filters.switchedOn[key] != null && typeof(cms.filters.switchedOn[key][2]) == 'function' )
			{
				// Call handler that will switch off editor and showed up simple textarea
				cms.filters.switchedOn[key][2]( textarea_id );
			}
		}
		catch(e)
		{
			//cms.error('Errors with filter switch off!', e);
		}
		
		// Remove editor from switchedOn editors stack
		if( cms.filters.switchedOn[key] != undefined || cms.filters.switchedOn[key] != null )
		{
			cms.filters.switchedOn[key] = null;
		}
	}
};


// Pages init
cms.init = {};
cms.init.callbacks = [];

cms.init.add = function(rout, callback)
{
	if (typeof(callback) != 'function')
		return false;
	
	if (typeof(rout) == 'object')
	{
		for (var i=0; i < rout.length; i++)
			cms.init.callbacks.push([rout[i], callback]);
	}
	else if (typeof(rout) == 'string')
		cms.init.callbacks.push([rout, callback]);
	else
		return false;
};

cms.init.run = function()
{
	var body_id = $('body:first').attr('id').toString();
	
	for (var i=0; i < cms.init.callbacks.length; i++)
	{
		var rout_to_id = 'body_' + cms.init.callbacks[i][0];
		
		if (body_id == rout_to_id)
			cms.init.callbacks[i][1]();
	}
};


cms.init.add('page_index', function()
{	
	// Read coockie of expanded pages
	var matches = document.cookie.match(/expanded_rows=(.+?);/);
	var expanded_pages = matches ? matches[1].split(',') : [];
	
	var arr = [];
	
	for( var i=0; i < expanded_pages.length; i++ )
	{
		if( typeof(parseInt(expanded_pages[i])) == 'number' )
			arr[i] = parseInt(expanded_pages[i]);
	}
	
	expanded_pages = arr;
	
	
	var expandedPagesAdd = function( page_id )
	{
		expanded_pages.push(page_id);
		
		document.cookie = "expanded_rows=" + jQuery.unique(expanded_pages).join(',');
	};
	
	var expandedPagesRemove = function( page_id )
	{
		expanded_pages = jQuery.grep(expanded_pages, function(value, i){
			return value != page_id;
		});
		
		document.cookie = "expanded_rows=" + jQuery.unique(expanded_pages).join(',');
	}
	
	
	$('#pageMapItems .item-expander').live('click', function(){
		var li        = $(this).parent().parent();
		var parent_id = li.attr('rel');
		
		var expander = $(this);
		
		if ( ! li.hasClass('item-expanded'))
		{
			var level = parseInt(li.parent().attr('class').substring(10));
			//alert(level);
			// When information of page reordering updated
			var success_handler = function( html )
			{
				li.append( html );
				
				cms.cssZebraItems('.map-items .item');
				
				//li.find('ul .page-expander').click(frogPages.expanderClick);
				
				expander
					.addClass('item-expander-expand');
				li.addClass('item-expanded');
				
				expandedPagesAdd(parent_id);
				
				cms.loader.hide();
			};
			
			// When ajax error of updating information about page position
			var error_handler = function( html )
			{
				cms.error( 'Ajax: Sub pages not loaded!', html );
				
				cms.loader.hide();
			}
			
			cms.loader.show();
			
			// Sending information about page position to frog
			jQuery.ajax({
				// options
				url:      CMS_URL + ADMIN_DIR_NAME + '/page/children/' + parent_id + '/' + level,
				dataType: 'html',
				
				// events
				success: success_handler,
				error:   error_handler
			});
		}
		else
		{
			if ( expander.hasClass('item-expander-expand'))
			{
				expander.removeClass('item-expander-expand');
				li.find('> ul').hide();
				
				expandedPagesRemove(parent_id);
			}
			else
			{
				expander.addClass('item-expander-expand');
				li.find('> ul').show();
				
				expandedPagesAdd(parent_id);
			}
		}
	});

	
	// Add, remove
	$('#pageMapItems .item-add-button').live('click', function()
	{
		location.href = $(this).attr('rel');
	});
	
	$('#pageMapItems .item-remove-button').live('click', function()
	{
		if (confirm(__('Are you sure?')))
		{
			location.href = $(this).attr('rel');
		}
		
		return false;
	});
	
	
	// Reordering	
	$('#pageMapReorderButton').click(function()
	{
		$pageMapUl = $('#pageMapItems > li > ul');
		
		if ($('#pageMapCopyButton').hasClass('button-active'))
		{
			$('#pageMapCopyButton').removeClass('button-active');
			
			$pageMapUl
				.removeClass('map-drag')
				.sortable('destroy')
					.find('li')
					.draggable('destroy');
		}
		
		if ( ! $pageMapUl.hasClass('map-drag'))
		{			
			var dragStart_handler = function(event, ui)
			{
				ui.item.find('ul').hide();
			};
			
			var dragOver_handler = function(event, ui)
			{
				var level = parseInt(ui.placeholder.parent().attr('class').substring(10));
				ui.placeholder.css('margin-left', (32*level) + 'px');
			};
			
			var dragStopped_handler = function(event, ui)
			{
				ui.item.find('ul').show();
				
				var ul        = ui.item.parent();
				var parent_id = parseInt( ul.parent().attr('rel') );
				
				var li = ul.children('li');
				
				var pages_ids = [];
				
				for( var i=0; i < li.length; i++ )
				{
					var child_id = $(li[i]).attr('rel');
					
					if (child_id !== undefined)
						pages_ids.push(child_id);
				}
				
				pages_ids = pages_ids.reverse();
				
				var success_handler = function()
				{
					cms.loader.hide();
				};
				
				var error_handler = function()
				{
					cms.error('Ajax return error (pages reordering).');
					cms.loader.hide();
				};
				
				cms.loader.show();
				
				// Save reordered positons
				jQuery.ajax({
					// options
					url:  CMS_URL + ADMIN_DIR_NAME + '/page/reorder/' + parent_id,
					type: 'post',
					
					data: { pages: pages_ids },
					
					// events
					success: success_handler,
					error:   error_handler
				});
				
				// stylizate .map-items 
				cms.cssZebraItems('.map-items .item');
			};
		
			// Begin sorting
			$pageMapUl
				.addClass('map-drag')
				.sortable({
					// options
					axis: 'y',
					items: 'li',
					connectWith: 'ul',
					placeholder: 'map-placeholder',
					opacity: 0.7,
					forceHelperSize: true,
					grid: [5, 8],
					cursor:'move',
					
					// events
					start: dragStart_handler,
					over:  dragOver_handler,
					stop:  dragStopped_handler
				});
				
			$(this).addClass('button-active');
		}
		else
		{
			$pageMapUl
				.removeClass('map-drag')
				.sortable('destroy');
			
			$(this).removeClass('button-active');
		}
	});
	
	
	// Copy pages
	$('#pageMapCopyButton').click(function()
	{
		$pageMapUl = $('#pageMapItems > li > ul');
		
		if ($('#pageMapReorderButton').hasClass('button-active'))
		{
			$('#pageMapReorderButton').removeClass('button-active');
			
			$pageMapUl
				.removeClass('map-drag')
				.sortable('destroy');
		}
		
		if ( ! $pageMapUl.hasClass('map-drag'))
		{
			// when dragging start
			var dragStart_handler = function(event, ui)
			{
				ui.helper.find('ul').hide();
			};
			
			// when draged element appended to container
			var dragOver_handler = function( event, ui )
			{
				var level = parseInt(ui.placeholder.parent().attr('class').substring(10));
				ui.placeholder.css('margin-left', (32*level) + 'px');
			};
			
			// when element dropped
			var dragStopped_handler = function( event, ui )
			{
				ui.helper.find('ul').show();
				
				var ul        = ui.item.parent();
				var page_id   = parseInt( ui.item.attr('rel') );
				var parent_id = parseInt( ul.parent().attr('rel') );
				
				var li = ul.children('li');
				
				var pages_ids = [];
				
				for( var i=0; i < li.length; i++ )
				{
					var child_id = $(li[i]).attr('rel');
					
					if (child_id !== undefined)
						pages_ids.push(child_id);
				}
				
				pages_ids = pages_ids.reverse();
				
				var success_handler = function()
				{
					cms.loader.hide();
					location.reload();
				};
				
				var error_handler = function()
				{
					cms.error('Ajax return error (pages reordering).');
					cms.loader.hide();
				};
				
				cms.loader.show();
				
				// Save reordered positons
				jQuery.ajax({
					// options
					url:      CMS_URL + ADMIN_DIR_NAME + '/page/copy/' + parent_id,
					type:     'post',
					dataType: 'json',
					
					data: {
						dragged_id: page_id,
						pages:      pages_ids
					},
					
					// events
					success: success_handler,
					error:   error_handler
				});
				
				// stylizate .map-items 
				cms.cssZebraItems('.map-items .item');
			};
			
			// Begin sorting
			$pageMapUl
				.addClass('map-drag')
				.sortable({
					// options
					axis:        'y',
					items:       'li',
					connectWith: 'ul',
					placeholder: 'map-placeholder',
					opacity:     0.7,
					forceHelperSize: true,
					grid:        [5, 8],
					
					// events
					start:      dragStart_handler,
					over:       dragOver_handler,
					beforeStop: dragStopped_handler
				})
				.find('li')
				.draggable({
					// options
					axis:        'y',
					items:       'li',
					connectToSortable: $pageMapUl,
					placeholder: 'map-placeholder',
					opacity:     0.7,
					forceHelperSize: true,
					helper:      'clone',
					grid:        [5, 8]
				});
				
			$(this).addClass('button-active');
		}
		else
		{
			$pageMapUl
				.removeClass('map-drag')
				.sortable('destroy')
					.find('li')
					.draggable('destroy');
			
			$(this).removeClass('button-active');
		}
	});
	
	
	// Search
	var search = function( query )
	{
		var success_handler = function( data )
		{
			$('#pageMapSearchItems')
				.removeClass('map-wait')
				.html( data );
			
			cms.cssZebraItems('.map-items .item');
		};
		
		var error_handler = function()
		{
			cms.error('Search: Ajax return error.');
		};
		
		$('#pageMapItems').hide();
		$('#pageMapSearchItems')
			.addClass('map-wait')
			.show();
		
		$.ajax({
			url:      CMS_URL + ADMIN_DIR_NAME + '/page/search/',
			type:     'post',
			dataType: 'html',
			
			data: { query: query },
			
			success: success_handler,
			error:   error_handler
		});
	};
	
	var search_timeout;
	
	$('#pageMapSearchField').bind('keyup', function(event){
		var val = $(this).val();
		
		clearTimeout(search_timeout);
		
		if (val !== '')
		{			
			if (event.keyCode == 13)
				search( val );
			else
				search_timeout = setTimeout(function(){ search(val); }, 1000);
		}
		else
		{
			$('#pageMapItems').show();
			$('#pageMapSearchItems').hide();
		}
	});
}); // end init page/index


cms.init.add(['page_add', 'page_edit'], function()
{
	// Datepicker
	$('#pageEditOptions input[name="page[published_on]"]').datepicker({
		// options
		dateFormat: 'yy-mm-dd',
		
		// events
		onSelect: function( dateText, inst )
		{
			inst.input.val( dateText +' 00:00:00' );
		}
	});
	

	// Slug & metadata
	var slug_is_fresh = false;
	
	$('#pageEditMetaTitleField').focus();
	
	$('#pageEditMetaTitleField').keyup(function()
	{
		if ( $('#pageEditMetaSlugField').val() == '' )
			slug_is_fresh = true;
		
		if (slug_is_fresh)
		{
			var new_slug = cms.convertSlug($(this).val()).replace(/-$/, '');
			
			$('#pageEditMetaSlugField').val(new_slug);
		}
		
		$('#pageEditMetaBreadcrumbField').val( $(this).val() );
	});
	
	$('#pageEditMetaMoreButton').click(function()
	{
		$('#pageEditMetaMore').slideToggle();
		
		return false;
	});
	
	$('#pageEditParts .item-options-button').live('click', function()
	{
		$(this).parent().parent().find('.item-options').slideToggle();
		
		return false;
	});
	
	$('#pageEditParts .item-content textarea').tabby();
	
	$('#pageEditMetaSlugField').keyup(function()
	{
		$(this).val( cms.convertSlug($(this).val()) );
	});
	
	
	// Parts
	$('#pageEditParts .item-filter').live('change', function()
	{
		var textarea_id = 'pageEditPartContent-'+jQuery(this).attr('rel');
		
		cms.filters.switchOn( textarea_id, jQuery(this).val() );
	});
	
	$('#pageEditParts .item-remove').live('click', function()
	{
		if (confirm(__('Are you sure?')))
		{
			$(this).parent().parent().parent().remove();
		}
		
		return false;
	});
	
	$('#pageEditPartAddButton').click(function()
	{
		var $form = $('<form class="dialog-form">'+
		'<p><label>'+__('Page part name')+'</label><span><input class="input-text" type="text" name="part_name" /></span></p>'+
		'</form>');
		
		var buttons = {};
		
		var buttons_add_action = function()
		{
			var part_name = $form.find('input[name="part_name"]').val().toLowerCase()
				.replace(/[^a-z0-9\-\_]/g, '_')
				.replace(/ /g,      '_')
				.replace(/_{2,}/g,  '_')
				.replace(/^_/,      '' )
				.replace(/_$/,      '' );
			
			$form.find('input[name="part_name"]').val(part_name);
			
			if (part_name == '')
			{
				alert(__('Part name can\'t be empty! Use english chars a-z, 0-9 and _ (underline char).'));
				
				$form.find('input[name="part_name"]').focus();
			}
			else
			{
				var part_index = parseInt($('#pageEditParts .item:last').attr('id').substring(13)) + 1;
				
				$(this).dialog('close');
				
				cms.loader.show();
				
				$.ajax({
					url:      CMS_URL + ADMIN_DIR_NAME + '/page/add_part',
					type:     'POST',
					dataType: 'html',
					
					data: {
						name:  part_name,
						index: part_index
					},
					success: function( html_data )
					{
						cms.loader.hide();
						
						$('#pageEditParts').append(html_data);
						$('#pageEditParts .item-content:last textarea').tabby();
					},
					error: function()
					{
						cms.error('Ajax error!');
					}
				});
			}
			
			return false;
		};
		
		buttons[__('Add')] = buttons_add_action;
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};
		
		$form.submit(buttons_add_action);
		
		$form.dialog({
			width:     235,
			modal:     true,
			buttons:   buttons,
			resizable: false,
			title:     __('Creating page part')
		});
		
		$form.find('input[name="part_name"]')
			.keyup(function(){
				$(this).val( cms.convertSlug($(this).val()).replace(/[^a-z0-9\-\_]/, '') );
			});
		
		return false;
	});
}); // end init page/add, page/edit


cms.init.add('layout_index', function()
{
	$('#layoutMapAddButton').click(function()
	{
		location.href = $(this).attr('rel');
	});
	
	$('#layoutMap .item-remove-button').click(function()
	{
		if (confirm(__('Are you sure?')))
		{
			location.href = $(this).attr('rel');
		}
		
		return false;
	});
}); // end init layout/index


cms.init.add(['layout_add', 'layout_edit'], function()
{
	$('#layoutEditNameField').focus();
	
	$('#layoutEditNameField').keyup(function()
	{
		var val = $(this).val()
					.replace(/ /g, '_')
					.replace(/[^a-z0-9\_\-\.]/ig, '');
		
		$(this).val(val);
	});
	
	$('#layoutEditContentField').tabby();
}); // end init layout/add, layout/edit


cms.init.add('snippet_index', function()
{
	$('#snippetMapAddButton').click(function()
	{
		location.href = $(this).attr('rel');
	});
	
	$('#snippetMap .item-remove-button').click(function()
	{
		if (confirm(__('Are you sure?')))
		{
			location.href = $(this).attr('rel');
		}
		
		return false;
	});
}); // end init snippet/index


cms.init.add(['snippet_add', 'snippet_edit'], function()
{
	$('#snippetEditNameField').focus();
	
	$('#snippetEditNameField').keyup(function()
	{
		var val = $(this).val()
					.replace(/ /g, '_')
					.replace(/[^a-z0-9\_\-\.]/ig, '');
		
		$(this).val(val);
	});
	
	$('#snippetEditContentField').tabby();
}); // end init snippet/add, snippet/edit


cms.init.add('plugins_index', function()
{
	$('#pluginsMapAddButton').click(function()
	{
		alert(__('Sorry, this functionality is not available now!'));
	});
	
	$('#pluginsMapItems .item-activate-button, #pluginsMapItems .item-deactivate-button').click(function()
	{
		location.href = $(this).attr('rel');
	});
	
	$('#pluginsMapItems .item-docs-button').click(function()
	{
		location.href = $(this).attr('rel');
	});
	
	$('#pluginsMapItems .item-settings-button').click(function()
	{
		location.href = $(this).attr('rel');
	});
}); // end init plugins/index


cms.init.add('user_index', function()
{
	$('#userMapAddButton').click(function()
	{
		location.href = $(this).attr('rel');
	});
	
	$('#userMapItems .item-remove-button').click(function()
	{
		if (confirm(__('Are you sure?')))
		{
			location.href = $(this).attr('rel');
		}
		
		return false;
	});
});

cms.init.add(['user_add', 'user_edit'], function()
{
	$('#userEditNameField').focus();
});


// Run
jQuery(document).ready(function()
{
	if( $.browser.msie )
		$('html:first').addClass('msie');
	
	$('#noscript').hide();
	
	// loader
	cms.loader.init();
	
	// messages
	cms.messages.init();
	
	// init
	cms.init.run();
	
	// stylizate .map-items 
	cms.cssZebraItems('.map-items .item');
});


// IE HTML5 hack (If you like to work with IE - you have a big problems ^___^ )
if (document.all)
{
	var e = ['header', 'nav', 'aside', 'article', 'section', 'footer', 'figure', 'hgroup', 'mark', 'output', 'time'];
	for(i in e) document.createElement(e[i]);
}