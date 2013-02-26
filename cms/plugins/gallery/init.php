<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'gallery',
	'title' => 'Gallery',
	'description' => '',
	'version' => '1.0.0',
	'javascripts' => 'gallery.js',
	'css' => 'gallery.css'
) )->register();

if($plugin->enabled())
{	
	if(IS_BACKEND)
	{
		Route::set( 'view_category', ADMIN_DIR_NAME.'/photos/category(/<id>)', array(
			'id' => '[0-9]+'
		) )
			->defaults( array(
				'controller' => 'photos',
				'action' => 'index'
			) );
	}
}

