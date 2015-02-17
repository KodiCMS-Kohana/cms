cms.init.add(['snippet_edit', 'snippet_add'], function () {
	$('#textarea_content').on('filter:switch:on', function(e, editor) {
		$('.panel').setHeightFor('#textarea_contentDiv', {
			contentHeight: true,
			updateOnResize: true,
			offset: 30,
			minHeight: 300,
			onCalculate: function(a, h) {
				cms.filters.exec('textarea_content', 'changeHeight', h);
			},
			onResize: function(a, h) {
				cms.filters.exec('textarea_content', 'changeHeight', h);
			}
		});
	});

	set_editor(SNIPPET_EDITOR);
	
	$('select[name="editor"]').on('change', function() {
		set_editor($(this).val());
	});

	function set_editor($editor) {
		cms.filters.switchOn('textarea_content', $editor, $('#textarea_content').data());
	}
});

