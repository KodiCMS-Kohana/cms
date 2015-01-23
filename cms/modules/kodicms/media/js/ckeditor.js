cms.plugins.ckeditor = {};

CKEDITOR.disableAutoInline = true;
CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = '/backend/elfinder/';
};
cms.plugins.ckeditor.switchOn_handler = function (textarea_id, params) {
	params = $.extend({
		skin: 'bootstrapck',
		height: 200
	}, params);
	var editor = CKEDITOR.replace(textarea_id, params);
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
		case 'changeHeight':
			editor.resize('100%', data);
	}
}

$(function () {
	cms.filters.add('ckeditor', cms.plugins.ckeditor.switchOn_handler, cms.plugins.ckeditor.switchOff_handler, cms.plugins.ckeditor.exec_handler);
});