<?php defined('SYSPATH') or die('No direct access allowed.');

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