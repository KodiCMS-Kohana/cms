/**
 * Files ajax manager dialog
 * 
 * Unsing:
 * cms.plugins.fileManager({
 * 	multiple: true,
 * 	buttonText: __('Insert!'),
 * 	callback: function(files)
 * 	{
 * 		console.log(files);
 * 	}
 * });
 */
cms.plugins.fileManager = function( options )
{
	if( options === undefined)
		throw 'Argiment \'options\' not found. Syntax: fileManager( options )';
		
	var self = this;
	var $dialog;
	var loaded_files = {};
	
	var now_path = options.path || '';
	var button_select_text = options.buttonText ? options.buttonText: __('Select');
	
	var html = '<div class="fm-dialog-map map">'
			  +'<div class="map-header"><span class="name">'+__('File name')+'</span><span class="size">'+__('Size')+'</span></div>'
              +'<ul class="fm-items map-items">'
              +'</ul>'
              +'</div>';
	
	var dialogOptions = {
		modal:       true,
		width:       500,
		resizable:   false,
		title:       __('File manager'),
		buttons:     {},
		zIndex:      400000
	};
	
	// Upload files
	dialogOptions.buttons[__('Upload files')] = function()
	{
		cms.plugins.uploader({
			folder: now_path,
			callback: function( files )
			{				
				if (files.length > 0)
				{
					$items = $dialog.find('.map-items');
					
					for(var i=0; i<files.length; i++)
					{
						loaded_files[ files[i].name ] = files[i];
						
						$items.append('<li><div class="item"><span class="checkbox"><input type="'+ (options.multiple ? 'checkbox' : 'radio') +'" name="check[]" value="'+files[i].name+'" /></span> <span class="image"><img src="'+ PLUGINS_URL +'file_manager/images/files/'+(files[i].icon ? files[i].icon: 'file.png')+'" /></span> <span class="name">'+files[i].name+'</span><span class="size">'+files[i].size+'</span></div></li>');
					}
				}
			}
		});
	};
	
	// Create folder dialog
	dialogOptions.buttons[__('Create folder')] = function()
	{
		var html = '<form class="dialog-form" onsubmit="return false;">'
		          +'<p><label>'+__('Folder name')+'</label><input type="text" name="name" value="" /></p>'
				  +'</form>';
		
		var buttons = {};
		
		buttons[__('Create')] = function()
		{
			var folder_name = $(this).find('input[name="name"]').val();
			
			$d = $(this);
			
			cms.loader.show();
			
			$.ajax({
				url: CMS_URL + ADMIN_DIR_NAME + '/plugin/file_manager/create_folder_json/' + now_path + folder_name,
				type: 'GET',
				dataType: 'json',
				success: function(data)
				{
					cms.loader.hide();
					$dialog.find('.map-items').prepend('<li><div class="item"><span class="checkbox"><input type="'+ (options.multiple ? 'checkbox' : 'radio') +'" name="check[]" value="'+folder_name+'" /></span> <span class="image"><img src="'+ PLUGINS_URL +'file_manager/images/folder.png" /></span> <span class="name"><a href="javascript:;" rel="'+folder_name+'" role="folder">'+folder_name+'</a></div></li>');
					$d.dialog('close');
				},
				error: function()
				{
					cms.loader.hide();
					cms.error('Ajax error (Creating folder)!');
					$d.dialog('close');
				}
			});
		};
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};
		
		$(html).dialog({
			modal:     true,
			width:     250,
			resizable: false,
			title:     __('Create folder'),
			buttons:   buttons
		});
	};
	
	// Select button
	dialogOptions.buttons[button_select_text] = function()
	{
		var files = [];
		
		$(this).find('.map-items input[name="check[]"]:checked').each(function()
		{
			var lf = loaded_files[ $(this).val() ];
			
			if (lf !== undefined)
			{
				lf.path = PUBLIC_URL + now_path + lf.name;				
				files.push( lf );
			}
		});
		
		$(this).dialog('close');
		
		if (options.callback)
			options.callback(files);
		
		$(this).dialog('destroy');
	};
	
	// Cancel button
	dialogOptions.buttons[__('Cancel')] = function()
	{
		$(this).dialog('close');
		
		if (options.callback)
			options.callback(false);
			
		$(this).dialog('destroy');
	};
	
	$dialog = $(html).dialog(dialogOptions);
	
	$dialog.parent().find('.ui-dialog-buttonset button:nth-child(2)').css('margin-right', '15px');
	
	// Load files
	var loadFiles = function( path )
	{
		var path = path || '';
		
		$dialog.find('.map-items')
			.html('')
			.addClass('map-wait');
		
		var success_handler = function(data)
		{
			var $items = $dialog.find('.map-items');
			
			$items
				.html('')
				.removeClass('map-wait');
			
			var title_html = '';
			
			if ( path != '' )
			{
				var slugs = path.split('/');
				
				slugs = $.grep(slugs,function(n,i){
					return(n);
				});
				
				title_html = '<a href="javascript:;" rel="">public</a> / ';
				
				var spath = '';
				
				for (var i=0; i<slugs.length; i++)
				{
					if (i+1 >= slugs.length)
					{
						$items.append('<li><div class="item"><span class="image"><img src="'+ PLUGINS_URL +'file_manager/images/folder-up.png" /></span> <span class="name"><a href="javascript:;" rel=".." role="folder">'+__('Level up')+'</a></span></div></li>');
					}
					
					if (i+1 < slugs.length)
					{
						title_html += '<a href="javascript:;" rel="'+spath+slugs[i]+'/">'+slugs[i]+'</a> / ';
						spath += slugs[i]+'/';
					}
					else
					{
						title_html += slugs[i];
					}
				}
			}
			else
			{
				title_html = 'public';
			}
			
			$dialog.dialog('option', 'title', title_html);
			
			$dialog.parent().find('.ui-dialog-titlebar a').click(function()
			{
				loadFiles($(this).attr('rel'));
			});
			
			loaded_files = {};
			
			for (var i=0; i<data.length; i++)
			{
				if ( ! data[i].is_dir) continue;
				
				$items.append('<li><div class="item"><span class="checkbox"><input type="'+ (options.multiple ? 'checkbox' : 'radio') +'" name="check[]" value="'+data[i].name+'" /></span> <span class="image"><img src="'+ PLUGINS_URL +'file_manager/images/folder.png" /></span> <span class="name"><a href="javascript:;" role="'+(data[i].is_dir ? 'folder': 'file')+'" rel="'+data[i].name+'">'+data[i].name+'</a></span></div></li>');
			}
			
			for (var i=0; i<data.length; i++)
			{
				if (data[i].is_dir) continue;
				
				loaded_files[data[i].name] = data[i];
				$items.append('<li><div class="item"><span class="checkbox"><input type="'+ (options.multiple ? 'checkbox' : 'radio') +'" name="check[]" value="'+data[i].name+'" /></span> <span class="image"><img src="'+ PLUGINS_URL +'file_manager/images/files/'+(data[i].icon ? data[i].icon: 'file.png')+'" /></span> <span class="name">'+data[i].name+'</span><span class="size">'+data[i].size+'</span></div></li>');
			}
			
			$('.fm-items .item')
				.unbind('click')
				.bind('click', function(e)
				{
					if (e.target !== undefined && e.target.tagName != 'INPUT')
					{
						var inp = $(this).find('input[name="check[]"]');
						inp.attr('checked', (inp.attr('checked') ? false: true));
					}
				});
			
			now_path = path;
		};
		
		var error_handler = function(data)
		{
			cms.error('Error when files ajax loading...', data);
		};
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: CMS_URL + ADMIN_DIR_NAME + '/plugin/file_manager/files_json/'+path,
			
			success: success_handler,
			error: error_handler
		});

	}; // loadFiles
	
	$('.fm-items a[role="folder"]').live('click', function()
	{
		var dir = $(this).attr('rel');
		var p = now_path + dir + '/';
		
		if (now_path != '' && dir == '..')
			p = now_path.replace(/[^\/]*\/$/, '');
		
		loadFiles(p);
	});
	
	loadFiles(now_path);
	
}; // end cms.plugins.fileManager



/**
 * Files ajax uploader dialog
 * 
 * Unsing:
 * cms.plugins.uploader({
 * 	folder: FILE_MANAGER_NOW_PATH,
 * 	callback: function( files )
 * 	{
 * 		if (files.length > 0)
 * 			// work with files
 * 	}
 * });
 */
cms.plugins.uploader = function(options)
{
	if ( options === undefined)
		throw 'Argiment \'options\' not found. Syntax: uploader( options )';
	
	if ( options.folder == undefined )
		throw 'Param \'options.folder\' is required!';
	
	var self = this;
	var uploaded_files = [];
	var upload_url = options.upload_url || CMS_URL + ADMIN_DIR_NAME + '/plugin/file_manager/upload';
	
	var uploadFinish_handler = function()
	{
		if (options.callback)
			options.callback(uploaded_files);
	};
	
	var dialogOptions = {
		modal:     true,
		width:     300,
		resizable: false,
		title:     __('Upload files'),
		buttons:   {},
		close:     uploadFinish_handler
	};
	
	var layoutHTML = '<form class="fm-fileuploader-form" action="upload.php" method="POST" enctype="multipart/form-data">'
	                +'<div class="fm-fileuploader">'
					+'<noscript>'
				    +'<p>Please enable JavaScript to use file uploader.</p>'
				    +'</noscript>'
				    +'</div>'
					+'</form>';
	
	dialogOptions.buttons[__('Close')] = function()
	{
		$(this).dialog('close');
	};
	
	$form = $(layoutHTML).dialog(dialogOptions);
	
	var onComplete_handler = function(id, fileName, responseJSON)
	{
		responseJSON.path = PUBLIC_URL + options.folder + '/' + responseJSON.filename;
		responseJSON.name = responseJSON.filename;
		
		uploaded_files.push(responseJSON);
	}
	
	var uploaderOptions = {
		element:    $form.find('.fm-fileuploader')[0],
		action:     upload_url,
		debug:      false,
		buttonText: __('Click or drag-n-drop files here'),
		params: {
			folder: options.folder
		},
		onComplete: onComplete_handler,
		messages: {
            typeError:    __('{file} has invalid extension. Only {extensions} are allowed.'),
            sizeError:    __('{file} is too large, maximum file size is {sizeLimit}.'),
            minSizeError: __('{file} is too small, minimum file size is {minSizeLimit}.'),
            emptyError:   __('{file} is empty, please select files again without it.'),
            onLeave:      __('The files are being uploaded, if you leave now the upload will be cancelled.')
        },
		showMessage: function(message) {
			alert(message);
		}
	};
	
	if (options.params != undefined)
	{
		for (var k in options.params)
			uploaderOptions.params[k] = options.params[k];
	}
	
	var uploader = new qq.FileUploader(uploaderOptions);
};


/*
* Init for files manager pages
*/
cms.init.add('plugin_file_manager_index', function()
{	
	$('#FMMapUploadButton').click(function()
	{
		cms.plugins.uploader({
			folder: FILE_MANAGER_NOW_PATH,
			callback: function( files )
			{
				if (files.length > 0)
					location.reload();
			}
		});
	});
	
	$('#FMMapCFolderButton').click(function()
	{
		var html = '<form class="dialog-form" onsubmit="return false;">'
		          +'<p><label>'+__('Folder name')+'</label><input type="text" name="name" value="" /></p>'
				  +'</form>';
		
		var buttons = {};
		
		buttons[__('Create')] = function()
		{
			var folder_name = $(this).find('input[name="name"]').val();
			
			location.href = CMS_URL + ADMIN_DIR_NAME + '/plugin/file_manager/create_folder/' + FILE_MANAGER_NOW_PATH + '/' + folder_name;
		};
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};
		
		$form = $(html).dialog({
			modal:     true,
			width:     250,
			resizable: false,
			title:     __('Create folder'),
			buttons:   buttons
		});
		
		/*
		$form.find('input[name="name"]').keyup(function(){
			$(this).val($(this).val().replace(/[\.]/, '_'));
		});
		*/
	});
	
	$('#FMMapItems .item-remove-button').live('click', function()
	{
		if (confirm(__('Are you sure?')))
			location.href = $(this).attr('rel');
		
		return false;
	});
	
	$('#FMMapItems .item-rename-button').live('click', function()
	{
		var params = $.parseJSON($(this).attr('rel'));
		
		var html = '<form class="dialog-form" onsubmit="return false;">'
		          +'<p><label>'+__('Name')+'</label> <input type="text" name="name" /></p>'
				  +'<p><label>'+__('Permissions')+'</label> <input type="text" name="chmod" /></p>'
		          +'<form>';
		
		var buttons = {};
		
		buttons[__('Save')] = function()
		{
			var new_name = $(this).find('input[name="name"]').val();
			var new_chmod = $(this).find('input[name="chmod"]').val();
			
			location.replace( CMS_URL + ADMIN_DIR_NAME + '/plugin/file_manager/rename/'+params.now_path+'?old_name=' + params.name + '&new_name=' + new_name + '&new_chmod=' + new_chmod );
		};
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};
		
		$form = $(html).dialog({
			modal:     true,
			width:     250,
			resizable: false,
			title:     __('Rename'),
			buttons:   buttons
		});
		
		$form.find('input[name="name"]').val(params.name);
		$form.find('input[name="chmod"]').val(params.chmod);
		
		return false;
	});
});