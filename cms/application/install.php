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
define('IS_BACKEND', FALSE);
/**
 * Set the default cookie salt
 * 
 */
Cookie::$salt = 'install_system';

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'users'			=> MODPATH . 'users',
	'kodicms'		=> MODPATH . 'kodicms',		// Core
	'assets'		=> MODPATH . 'assets',		// Asset Manager
	'cache'			=> MODPATH . 'cache',		// Cache manager
	'database'		=> MODPATH . 'database',	// Database access
	'auth'			=> MODPATH . 'auth',		// Basic authentication
	'orm'			=> MODPATH . 'orm',			// Object Relationship Mapping,
	'minion'		=> MODPATH . 'minion',		// Minion
	'filesystem'	=> MODPATH . 'filesystem',
	'bootstrap'		=> MODPATH . 'bootstrap',
	'breadcrumbs'	=> MODPATH . 'breadcrumbs',
	'installer'		=> MODPATH . 'installer'
) );


$modules = Kohana::modules();
if( ! isset($modules['installer']) OR ! is_dir( MODPATH . 'installer' ))
{
	throw HTTP_Exception::factory(404, __('System not installed. Installer not found.'));
}

if (PHP_SAPI != 'cli')
{
	if( ! URL::match('install', Request::detect_uri()) )
	{
		$uri = Route::get('install')->uri();
	}
}