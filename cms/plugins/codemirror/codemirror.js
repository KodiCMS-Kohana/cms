/*
	Codemirror object
*/
cms.plugins.codemirror = {};


// Switch on tinymce handler
cms.plugins.codemirror.switchOn_handler = function( textarea_id, params )
{
	var local_params = {
		tabMode: 'indent',
		lineNumbers: true,
		lineWrapping: true,
		tabSize: 4,
		indentUnit: 4,
		mode: "application/x-httpd-php"
	};
	
	params = $.extend(local_params, params);
	cms.plugins.codemirror.editor = CodeMirror.fromTextArea(document.getElementById(textarea_id), params);
	
	cms.plugins.codemirror.editor.on('change', function(ctx, obj) {
		cms.plugins.codemirror.editor.save();
	});
	
	$(cms.plugins.codemirror.editor.display.wrapper).css($(document.getElementById(textarea_id)).data());
};

// Switch off tinymce handler
cms.plugins.codemirror.switchOff_handler = function( textarea_id )
{
	if(cms.plugins.codemirror.editor)
	{
		cms.plugins.codemirror.editor.toTextArea();
	}
}

cms.plugins.codemirror.exec_handler = function( textarea_id, data )
{
	
}

/*
	When DOM init
*/
jQuery(function(){

	cms.filters
		.add( 'codemirror', cms.plugins.codemirror.switchOn_handler, cms.plugins.codemirror.switchOff_handler, cms.plugins.codemirror.exec_handler );

	cms.filters.switchOn( 'highlight_content', 'codemirror' );
});

cms.init.add(['layout_edit', 'snippet_edit', 'layout_add', 'snippet_add'], function(){
	jQuery(function(){
		cms.filters.switchOn( 'textarea_content', 'codemirror', $('#textarea_content').data());
	});
})