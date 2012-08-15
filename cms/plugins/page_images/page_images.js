// Add page inits for pages page/edit, page/add
cms.init.add(['page_edit', 'page_add'], function()
{	
	$('#PIAddButton').click(function()
	{
		if (cms.plugins.uploader == undefined)
			cms.error('Plugin page_images reuire plugin files_manager!');
        
        var page_id = parseInt( $('#PIPlugin').attr('rel') );
		
		var uploader_callback_handler = function(files)
		{
			if (files.length > 0)
			{
                $('#PIList').load(CMS_URL + ADMIN_DIR_NAME + '/plugin/page_images/getImagesItems/'+page_id);
			}
		};
		
		cms.plugins.uploader({
			upload_url: CMS_URL + ADMIN_DIR_NAME + '/plugin/page_images/upload',
			multiple: true,
			folder:   'page_images',
			callback: uploader_callback_handler,
			params: {
				page_id: page_id
			}
		});
		
		return false;
	});
	
	$('#PIList .pi-remove-link').live('click', function()
	{
		cms.loader.show();
		
		var link_el = $(this);
		
		link_el.parent().css('opacity', 0.5);
		
		$.ajax({
			url: $(this).attr('href'),
			success: function(data)
			{
				cms.loader.hide();
				
				link_el.parent().remove();
			},
			error: function()
			{
				alert(__('Image not removed!'));
			}
		});
		
		return false;
	});
    
    $('#PIList').sortable({
        handler: 'label image',
        stop: function(event, ui) {
            var pos = [];
            $(this).find('li').each(function() {
                pos.push($(this).attr('rel'));
            });
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: CMS_URL + ADMIN_DIR_NAME + '/plugin/page_images/changePosition',
                data: {position: pos}
            });
        }
    });
	
}); // end cms.init.add page_edit, page_add