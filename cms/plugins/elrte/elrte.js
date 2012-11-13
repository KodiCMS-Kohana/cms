/*
	Redactor object
*/
cms.plugins.elrte = {};


// Switch on tinymce handler
cms.plugins.elrte.switchOn_handler = function( textarea_id, params )
{
	var local_params = {
		absoluteURLs: false,
		allowSource: true,
		allowTextNodes: false,
		fmAllow: true,
		cssClass : 'el-rte',
		height   : 250,
		toolbar  : 'kodicms',
		fmOpen : function(callback) {
			$('<div id="elfinder" />').elfinder({
				url : '/backend/elfinder/',
				dialog: { width : 900, modal : true, title : 'elFinder - file manager for web' },
				editorCallback : callback
			});
		},
		toolbars: {
			kodicms: ['undoredo', 'elfinder', 'style', 'alignment', 'colors', 'format', 'indent', 'lists', 'links', 'media', 'fullscreen']
		}
	};
	
	params = $.extend(local_params, params);
	
	$('#' + textarea_id).elrte(params);
};

// Switch off tinymce handler
cms.plugins.elrte.switchOff_handler = function( textarea_id )
{
	 // destroy editor
	$('#' + textarea_id).elrte('destroy');	
};

/*
	When DOM init
*/
jQuery(function(){
	cms.filters
		.add( 'elrte', cms.plugins.elrte.switchOn_handler, cms.plugins.elrte.switchOff_handler );
});