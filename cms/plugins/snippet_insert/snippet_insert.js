cms.init.add(['page_edit', 'page_add'], function()
{	
	$('#pageEdit .item-title').append('<a class="item-snippet_insert-button" href="#" title="'+__('Insert snippet')+'"><img src="'+PLUGINS_URL+'snippet_insert/document_stroke-icon.png" /></a>');
	
	$('#pageEdit .item-title .item-snippet_insert-button').live('click', function()
	{
		cms.loader.show();
		
		var $to_textarea = $(this).parent().parent().find('.item-content textarea');
		
		$.ajax({
			mathod: 'get',
			url: CMS_URL + ADMIN_DIR_NAME + '/plugin/snippet_insert/snippets_json/',
			dataType:'json',
			success: function(data)
			{
				cms.loader.hide();
				
				var html = '<div class="snippet_insert-dialog map">'
						  +'<div class="map-header"><span class="name">'+__('Name')+'</span></div>'
						  +'<ul class="map-items"></ul>'
						  +'</div>';
				
				var buttons = {};
				
				buttons[__('Close')] = function()
				{
					$(this).dialog('close');
				};
				
				
				var $dialog = $(html).dialog({
					width:     350,
					height:    300,
					modal:     true,
					buttons:   buttons,
					resizable: false,
					title:     __('Insert snippet')
				});
				
				$map_items = $dialog.find('.map-items');
				
				for (var i=0; i<data.length; i++)
				{
					$map_items.append('<li class="item"><a href="javascript:;">'+data[i].name+'</a></li>');
				}
				
				$map_items.find('.item a').click(function()
				{
					cms.loader.show();
					
					var snippet_name = $(this).html();
					
					$.ajax({
						mathod: 'get',
						url: CMS_URL + ADMIN_DIR_NAME + '/plugin/snippet_insert/snippet_info_json/' + snippet_name,
						dataType:'json',
						success: function(data)
						{
							cms.loader.hide();
							
							if (data.length > 0)
							{
								var html = '<form class="snippet_insert-dialog2 dialog-form" onsubmit="return false;">';
								
								for (var i=0; i<data.length; i++)
								{
									html += '<p><label>'+data[i].desc+'</label> <input type="input" name="'+data[i].name+'" /></p>';
								}
								
								html += '</form>';
								
								var buttons2 = {};
					
								buttons2[__('Insert')] = function()
								{
									var snip_params = [];
									
									$('.snippet_insert-dialog2').find('input').each(function()
									{
										if ($(this).val() != '')
											snip_params.push('\'' + $(this).attr('name') + '\' => \'' + $(this).val() + '\'');
									});
									
									var out = '<?php $this->includeSnippet(\''+snippet_name+'\''+ (snip_params.length > 0 ? ', array('+ snip_params.join(', ') +')': '') +'); ?>';
									
									$to_textarea.val($to_textarea.val() + out);
									
									$(this).dialog('close');
									$dialog.dialog('close');
								};
								
								buttons2[__('Cancel')] = function()
								{
									$(this).dialog('close');
								};
								
								$prop_dialog = $(html).dialog({
									width:     250,
									modal:     true,
									buttons:   buttons2,
									resizable: false,
									title:     __('Snippet params')
								});
							}
							else
							{
								var out = '<?php $this->includeSnippet(\''+snippet_name+'\'); ?>';
								$to_textarea.val($to_textarea.val() + out);
								$dialog.dialog('close');
							}
						},
						error: function(data)
						{
							cms.loader.hide();
							cms.error('Ajax: snippet_insert not loaded snippets from /plugin/snippet_insert/snippet_info/', data);
						}
					});
					
					return false;
				});
			},
			error: function(data)
			{
				cms.loader.hide();
				cms.error('Ajax: snippet_insert not loaded snippets from /plugin/snippet_insert/snippets_json/', data);
			}
		})
		
		return false;
	});
});