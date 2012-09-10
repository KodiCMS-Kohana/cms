<?php defined('SYSPATH') or die('No direct access allowed.');

define('DB_TYPE', 'mysql');
define('DB_SERVER', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('TABLE_PREFIX', '');

// CMS defaults
define('BASE_URL',		 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/');
define('ADMIN_DIR_NAME', 'admin');
define('ADMIN_URL',		 BASE_URL.ADMIN_DIR_NAME.'/');

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'database'		=> MODPATH . 'database', // Database access
	'auth'			=> MODPATH . 'auth', // Basic authentication
	'orm'			=> MODPATH . 'orm', // Object Relationship Mapping,
) );

Route::set( 'install', 'install(/<action>(/<id>))' )
	->defaults( array(
		'directory' => 'system',
		'controller' => 'install',
		'action' => 'index',
	) );