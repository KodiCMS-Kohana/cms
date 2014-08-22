cms.init.add(['snippet_edit', 'snippet_add'], function () {
	function calculateEditorHeight() {
		var conentH = cms.content_height;
		var h = $('.panel-heading').outerHeight(true) + $('.form-actions').outerHeight(true) + 80;
		
		return conentH - h;
	}
	
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('textarea_content', 'changeHeight', calculateEditorHeight());
	});
	
	$(window).resize(function() {
		$('#textarea_content').trigger('filter:switch:on')
	});
});