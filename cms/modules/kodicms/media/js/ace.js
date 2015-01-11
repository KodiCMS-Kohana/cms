cms.plugins.ace = {};

cms.plugins.ace.switchOn_handler = function (textarea_id, params) {
	var editor_id = getSlug(textarea_id) + 'Div';
	var textarea = $('#' + textarea_id).hide();
	var height = textarea.data('height') ? textarea.data('height') : 300;
	var mode = textarea.data('mode') ? textarea.data('mode') : 'php';
	var editArea = $('<div id="' + editor_id + '" />')
		.insertAfter(textarea)
		.css({
			height: height,
			fontSize: 14
		});

	var editor = ace.edit(editor_id);
	editor.setValue(textarea.val());

	editor.clearSelection();
	editor.getSession().setMode("ace/mode/" + mode);
	editor.getSession().setTabSize(4);
	editor.getSession().setUseSoftTabs(false);
	editor.getSession().setUseWrapMode(true);
	editor.getSession().on('change', function () {
		textarea.val(editor.getSession().getValue());
	});
	editor.setTheme("ace/theme/" + ACE_THEME);

	function fullscreen(editArea, editor, height) {
		var $menu = $('#main-menu').add('#main-navbar').add('#main-menu-bg');
		if (!editArea.data('fullscreen') || editArea.data('fullscreen') == 'off') {
			editArea
					.css({
						position: 'fixed',
						width: '100%',
						height: '100%',
						top: 0, left: 0,
						'z-index': 999
					})
					.data('fullscreen', 'on');
			
			$menu.hide();
		} else {
			editArea
					.data('fullscreen', 'off')
					.css({
						position: 'relative',
						width: 'auto',
						height: height,
						top: 'auto', left: 'auto'
					});
			$menu.show();
		}
	}

	if (textarea.data('readonly') == 'on') {
		editor.setReadOnly(true);
	} else {
		editor.commands.addCommand({
			bindKey: {win: 'Ctrl-S', mac: 'Command-S'},
			exec: function (editor) {
				$('button[name="continue"]').click();
			}
		});

		editor.commands.addCommand({
			name: 'Full-screen',
			bindKey: {win: 'Ctrl-Shift-F', mac: 'Command-F'},
			exec: function (editor) {
				fullscreen(editArea, editor, height)
			}
		});
	}

	return editor;
};

cms.plugins.ace.switchOff_handler = function (editor, textarea_id){
	$('#' + getSlug(textarea_id) + 'Div').remove();
}

cms.plugins.ace.exec_handler = function (editor, command, textarea_id, data){
	var textarea_id = getSlug(textarea_id);

	switch (command) {
		case 'insert':
			editor.insert(data);
			break;
		case 'changeHeight':
			$('#' + textarea_id + 'Div')
					.css({
						height: data
					});

			editor.resize();
	}

	return true;
}

$(function () {
	cms.filters
			.add('ace', cms.plugins.ace.switchOn_handler, cms.plugins.ace.switchOff_handler, cms.plugins.ace.exec_handler);

	if ($('#highlight_content').length > 0)
		cms.filters.switchOn('highlight_content', 'ace');
});

cms.init.add(['layout_edit', 'snippet_edit', 'layout_add', 'snippet_add'], function () {
	$(function () {
		if ($('#textarea_content').length > 0)
			cms.filters.switchOn('textarea_content', 'ace', $('#textarea_content').data());
	});
})