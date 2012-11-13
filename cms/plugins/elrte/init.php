<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'elrte',
	'title' => 'elRTE',
	'author' => 'ButscH',
	'description' => 'Open source WYSIWYG editor for website.',
	'javascripts' => array(
		'vendors/elrte/js/elrte.full.js',
		'vendors/elrte/js/i18n/elrte.ru.js',
	
		//'vendors/elfinder/js/elfinder.min.js',
		
		'vendors/elfinder/js/elFinder.js',
		'vendors/elfinder/js/elFinder.view.js',
		'vendors/elfinder/js/elFinder.ui.js',
		'vendors/elfinder/js/elFinder.quickLook.js',
		'vendors/elfinder/js/elFinder.eventsManager.js',
		
		'vendors/elfinder/js/i18n/elfinder.ru.js',
	
		'elrte.js'
	),
	'css' => array(
		'vendors/elrte/css/elrte.min.css',
		'vendors/elfinder/css/elfinder.css',
	)
) )
	->register();

if( $plugin->enabled() )
{
	Filter::add('elrte');
	
	Route::set( 'elfinder', ADMIN_DIR_NAME.'/elfinder(/<path>)', array(
		'path' => '.*'
	) )
		->defaults( array(
			'controller' => 'elfinder'
		) );
}