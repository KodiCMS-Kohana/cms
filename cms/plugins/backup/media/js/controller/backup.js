cms.init.add('backup_view', function() {
	function calculateEditorHeight() {
		var conentH = calculateContentHeight();
		var h = $('.widget-title').outerHeight(true) + $('.widget-header').outerHeight(true) + $('.form-actions').outerHeight(true) + 10;

		return conentH - h;
	}

	$('#highlight_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('highlight_content', 'changeHeight', calculateEditorHeight());
	});

	$(window).resize(function() {
		$('#highlight_content').trigger('filter:switch:on')
	});
})