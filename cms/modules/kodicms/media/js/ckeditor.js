cms.plugins.ckeditor = {};

cms.plugins.ckeditor.switchOn_handler = function (textarea_id, params) {
	var editor = CKEDITOR.replace(textarea_id, {
		skin: 'bootstrapck'
	});
	return editor;
};

cms.plugins.ckeditor.switchOff_handler = function (editor, textarea_id){
	editor.destroy()
}

cms.plugins.ckeditor.exec_handler = function (editor, command, textarea_id, data){
	switch (command) {
		case 'insert':
			editor.insertText(data);
			break;
	}
}

$(function () {
	cms.filters.add('ckeditor', cms.plugins.ckeditor.switchOn_handler, cms.plugins.ckeditor.switchOff_handler, cms.plugins.ckeditor.exec_handler);
});