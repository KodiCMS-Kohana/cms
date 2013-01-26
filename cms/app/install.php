<?php defined('SYSPATH') or die('No direct access allowed.');

define('DB_TYPE', 'mysql');
define('DB_SERVER', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('TABLE_PREFIX', '');
define('ADMIN_DIR_NAME', '');
define('PUBLIC_URL', BASE_URL . 'public/');
define('PLUGINS_URL', BASE_URL . 'cms/plugins/');

/**
 * Set the default cookie salt
 * 
 */
Cookie::$salt = 'install_system';

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'database'		=> MODPATH . 'database', // Database access
	'auth'			=> MODPATH . 'auth', // Basic authentication
	'orm'			=> MODPATH . 'orm', // Object Relationship Mapping,
	'bootstrap'		=> MODPATH . 'bootstrap',
	'breadcrumbs'	=> MODPATH . 'breadcrumbs',
) );

Route::set( 'install', 'install(/<action>(/<id>))' )
	->defaults( array(
		'directory' => 'system',
		'controller' => 'install',
		'action' => 'index',
	) );