/*
	Redactor object
*/
cms.plugins.redactor = {};


// Switch on tinymce handler
cms.plugins.redactor.switchOn_handler = function( textarea_id )
{
	$('#' + textarea_id).redactor({ 
		focus: true,
		wym: true,
		autoresize: false,
		lang: LOCALE
	});
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