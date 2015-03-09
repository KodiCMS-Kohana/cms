cms.init.add('update_index', function() {
	Api.get('update.check_files', {}, function(resp) {
		$('#files').html(resp.response);
	}, $('#files'));
});

cms.init.add('update_database', function() {
	$('#highlight_content').on('filter:switch:on', function(e, editor) {
		$('.panel').setHeightFor('#highlight_contentDiv', {
			contentHeight: true,
			updateOnResize: true,
			offset: 30,
			minHeight: 300,
			onCalculate: function(a, h) {
				cms.filters.exec('highlight_content', 'changeHeight', h);
			},
			onResize: function(a, h) {
				cms.filters.exec('highlight_content', 'changeHeight', h);
			}
		});
	});
	
	cms.filters.switchOn('highlight_content', DEFAULT_CODE_EDITOR, $('#highlight_content').data());
	
});