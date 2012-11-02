/*
	Redactor object
*/
cms.plugins.redactor = {};


// Switch on tinymce handler
cms.plugins.redactor.switchOn_handler = function( textarea_id, params )
{
	var local_params = {
		focus: true,
		//wym: true,
		autoresize: false,
		lang: LOCALE
	};
	
	params = $.extend(local_params, params);

	$('#' + textarea_id).redactor(params);
};

// Switch off tinymce handler
cms.plugins.redactor.switchOff_handler = function( textarea_id )
{
	 // destroy editor
	$('#' + textarea_id).destroyEditor();	
};

/*
	When DOM init
*/
jQuery(function(){

	cms.filters
		.add( 'redactor', cms.plugins.redactor.switchOn_handler, cms.plugins.redactor.switchOff_handler );
});