cms.init.add('update_index', function() {
	Api.get('update.check_files', {}, function(resp) {
		$('#files').html(resp.response);
	}, $('#files'));
});

cms.init.add('update_database', function() {
	function calculateEditorHeight() {
		var conentH = cms.content_height;
		var h = $('.widget-title').outerHeight(true) + $('.widget-header').outerHeight(true) + $('.form-actions').outerHeight(true) + 10;

		return conentH - h;
	}

	$('#highlight_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('highlight_content', 'changeHeight', calculateEditorHeight);
	});

	$(window).resize(function() {
		$('#highlight_content').trigger('filter:switch:on');
	});
	
	$('#highlight_content').trigger('filter:switch:on');
});