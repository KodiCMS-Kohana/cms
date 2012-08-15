// Add init for settings page
cms.init.add('plugin_image_resizing_settings', function(){
	
	$('#IRSettingsCacheAddButton').click(function(){
		
		var html = '<form class="dialog-form" onsubmit="return false;">'
		          +'<p><label>'+__('Width')+'</label><span><input type="text" name="width" value="" /></span></p>'
				  +'<p><label>'+__('Height')+'</label><span><input type="text" name="height" value="" /></span></p>'
				  +'</form>';
		
		var buttons = {};
		
		buttons[__('Add')] = function()
		{
			var w = $(this).find('input[name="width"]').val();
			var h = $(this).find('input[name="height"]').val();
			
			if (!w || !h)
			{
				alert(__('You should fill width and height fields!'));
			}
			else
			{			
				var size = w + 'x' + h;
				
				$('#IRSettingsCache').prepend('<i class="radio"><input id="IRSettingsCacheCkeckbox-'+size+'" type="checkbox" value="'+size+'" name="setting[cache_sizes][]" checked /> <label for="IRSettingsCacheCkeckbox-<?php echo $size; ?>">'+size+'</label></i>');
				
				$(this).dialog('close');
			}
		};
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};
		
		$dialog = $(html).dialog({
			modal: true,
			buttons: buttons,
			resizable: false,
			title: __('Add cache image size')
		});
		
		$dialog.find('input[name="width"], input[name="height"]').keyup(function()
		{
			$(this).val( $(this).val().replace(/[^0-9]/, '') );
		});
		
		return false;		
	});
	
}); // end cms.init.add 'plugin_image_resizing_settings'