cms.init.add(['snippet_edit', 'snippet_add'], function () {
	function calculateEditorHeight() {
		return $('#content').calcHeightFor('#textarea_contentDiv', {contentHeight: true});
	}
	
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('textarea_content', 'changeHeight', calculateEditorHeight());
	});
	
	$(window).resize(function() {
		$('#textarea_content').trigger('filter:switch:on')
	});
});