/*
	Ace object
*/
cms.plugins.ace = {};

cms.plugins.ace.switchOn_handler = function( textarea_id, params )
{
	var textarea = $('#' + textarea_id).hide();
	var height = textarea.data('height') ? textarea.data('height') : 300;
	var editArea = $('<div id=' + textarea_id + 'Div />')
		.insertAfter(textarea)
		.css({
			height: height,
			fontSize: 14
		});
	
	var editor = ace.edit(textarea_id + 'Div');
	editor.setValue(textarea.val());

	editor.clearSelection();
    editor.getSession().setMode("ace/mode/php");
	editor.getSession().setTabSize(4);
	editor.getSession().setUseSoftTabs(false);
	editor.getSession().setUseWrapMode(true);
	editor.getSession().on('change', function(){
		textarea.val(editor.getSession().getValue());
	});
	
	if(textarea.data('readonly') == 'off') {
//		editor.setTheme("ace/theme/monokai");
		editor.setReadOnly(true);
	} else {
		editor.commands.addCommand({
			name: 'myCommand',
			bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
			exec: function(editor) {
				$('button[name="continue"]').click();
			}
		});
	}
	
	
};

// Switch off tinymce handler
cms.plugins.ace.switchOff_handler = function( textarea_id )
{
	$('#' + textarea_id + 'Div').remove();
}

cms.plugins.ace.exec_handler = function( textarea_id, data )
{
	
}

/*
	When DOM init
*/
jQuery(function(){

	cms.filters
		.add( 'ace', cms.plugins.ace.switchOn_handler, cms.plugins.ace.switchOff_handler, cms.plugins.ace.exec_handler );

	cms.filters.switchOn( 'highlight_content', 'ace' );
});

cms.init.add(['layout_edit', 'snippet_edit', 'layout_add', 'snippet_add'], function(){
	jQuery(function(){
		cms.filters.switchOn( 'textarea_content', 'ace', $('#textarea_content').data());
	});
})