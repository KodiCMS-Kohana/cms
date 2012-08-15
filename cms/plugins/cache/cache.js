cms.init.add('plugin_cache_settings', function()
{
	$('#CSRemoveButton').click(function()
	{
		location.href = $(this).attr('rel');
		
		return false;
	});
});