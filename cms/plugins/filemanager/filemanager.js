cms.init.add('filemanager_index', function(){
	$("#upload_file").html5_upload({
		url: BASE_URL + '/filemanager/upload/' + FILEMANAGER_PATH,
		sendBoundary: true,
		onStart: function() {
			return true;
		},
		onProgress: function(event, progress, name, number, total) {
			$('#upload_file').hide();
			cms.loader.show();
		},
		onFinishOne: function(event, response, name, number, total) {
			$.event.trigger( "ajaxComplete", [{responseText:response}] );
			
			var row = $(response).hide();

			$('#filemanager-list table tbody').append(row.fadeIn(1000));
		},
		onFinish: function(event, response, name, number, total) {
			$('#upload_file').show();
			cms.loader.hide();
		}
	});
	
	$('#create-folder').click(function()
	{
		var self = $(this),
			cont = $(this).parent().parent(),
			tbody = cont.parent(),
			path = FILEMANAGER_PATH,
			chmod = self.text();

		var html = '<form class="dialog-form" onsubmit="return false;">'
		          +'<p><label>'+__('Folder name')+'</label><input type="text" name="name" value="" /></p>'
				  +'</form>';
		
		var buttons = {};
		
		buttons[__('Create')] = function()
		{
			var folder_name = $(this).find('input[name="name"]').val();
			
			$.post(BASE_URL + '/filemanager/folder/', {name: folder_name, path: path}, function(resp) {
				location.href = BASE_URL + '/filemanager/' + path;
			});
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
	});
	
	$('#filemanager-list .changeperms').live('click', function()
	{
		var self = $(this),
			cont = $(this).parent().parent(),
			tbody = cont.parent(),
			path = cont.data('path'),
			chmod = self.text();

		var html = '<form class="dialog-form" onsubmit="return false;">'
				  +'<p><label>'+__('Permissions')+'</label> <input type="text" name="chmod" /></p>'
		          +'<form>';

		var buttons = {};
		
		buttons[__('Save')] = function()
		{
			var $this = $(this);
			var new_chmod = $(this).find('input[name="chmod"]').val();
			$.post(BASE_URL + '/filemanager/chmod/', {chmod: new_chmod, path: path}, function(resp) {

				self.html(new_chmod);
				$this.dialog('close');
			}, 'json');
		};
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};

		$form = $(html).dialog({
			modal:     true,
			width:     250,
			resizable: false,
			title:     __('Change permissions'),
			buttons:   buttons
		});

		$form.find('input[name="chmod"]').val(chmod);
		
		return false;
	});
})