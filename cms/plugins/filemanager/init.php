<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'filemanager',
	'title' => 'File manager',
	'description' => 'Provides interface to manage files from the administration.',
	'javascripts' => 'filemanager.js'
) )->register();

if($plugin->enabled())
{	
	if(IS_BACKEND)
	{
		// Add navigation section
		Model_Navigation::add_section('Content', __('File manager'), 'filemanager', array('administrator', 'developer', 'editor'), 999);

		Route::set( 'view_file', ADMIN_DIR_NAME.'/filemanager/<action>(/<path>)', array(
			'action' => '(view|delete|upload|chmod|folder)',
			'path' => '.*'
		) )
			->defaults( array(
				'controller' => 'filemanager',
				'action' => 'view'
			) );

		Route::set( 'filemanager', ADMIN_DIR_NAME.'/filemanager(/<path>)', array(
			'path' => '.*'
		) )
			->defaults( array(
				'controller' => 'filemanager'
			) );
	}
}

