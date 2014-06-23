cms.init.add(['layout_edit', 'layout_add'], function () {
	function calculateEditorHeight() {
		var conentH = cms.content_height;
		var h = $('.widget-title').outerHeight(true) + $('.widget-header').outerHeight(true) + $('.form-actions').outerHeight(true) + 10;
		
		return conentH - h;
	}
	
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('textarea_content', 'changeHeight', calculateEditorHeight());
	});
	
	

	$(window).resize(function() {
		$('#textarea_content').trigger('filter:switch:on');
	});
});

cms.init.add('layout_index', function () {
	$('body').on('post:api-layout.rebuild', function(e, response) {
		if(!response) return;

		for(i in response) {
			$('.layout-block-list', '#layout_' + i).text((response[i] instanceof Array) ? response[i].sort().join (', ') : '')
		}
	});
});
