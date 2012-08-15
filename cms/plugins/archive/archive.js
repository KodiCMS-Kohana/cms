cms.init.add('plugin_archive_index', function() {
	$('#pageMapAddButton').click(function()
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
	
});
