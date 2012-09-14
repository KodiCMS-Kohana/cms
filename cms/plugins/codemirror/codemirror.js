/*
	Codemirror object
*/
cms.plugins.codemirror = {};


// Switch on tinymce handler
cms.plugins.codemirror.switchOn_handler = function( textarea_id )
{
	cms.plugins.codemirror.editor = CodeMirror.fromTextArea(document.getElementById(textarea_id), {
		tabMode: 'indent',
		lineNumbers: true,
		tabSize: 4,
        indentUnit: 4,
        indentWithTabs: true,
		mode: "application/x-httpd-php"
	});
};

// Switch off tinymce handler
cms.plugins.codemirror.switchOff_handler = function( textarea_id )
{
	if(cms.plugins.codemirror.editor)
	{
		cms.plugins.codemirror.editor.toTextArea();
	}
}

/*
	When DOM init
*/
jQuery(function(){

	cms.filters
		.add( 'codemirror', cms.plugins.codemirror.switchOn_handler, cms.plugins.codemirror.switchOff_handler );

	cms.filters.switchOn( 'highlight_content', 'codemirror' );
});

cms.init.add(['layout_edit', 'snippet_edit', 'layout_add', 'snippet_add'], function(){
	jQuery(function(){
		cms.filters.switchOn( 'textarea_content', 'codemirror' );
	});
})