<?php defined('SYSPATH') or die('No direct access allowed.');

if (PHP_SAPI != 'cli')
{
	define('IS_BACKEND', URL::match(ADMIN_DIR_NAME, Request::detect_uri()));
}

if( ! defined( 'IS_BACKEND' )) define('IS_BACKEND', FALSE);

// CMS defaults
define('ADMIN_URL',			BASE_URL . ADMIN_DIR_NAME . '/');
define('PLUGINS_URL',		BASE_URL . 'cms/plugins/');
define('PUBLIC_URL',		BASE_URL . 'public/');

define('PUBLICPATH',		DOCROOT . 'public' . DIRECTORY_SEPARATOR);
define('LAYOUTS_SYSPATH',	DOCROOT . 'layouts' . DIRECTORY_SEPARATOR);
define('SNIPPETS_SYSPATH',	DOCROOT . 'snippets' . DIRECTORY_SEPARATOR);

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set( DEFAULT_TIMEZONE );

/**
 * Set the default cookie salt
 */
Cookie::$salt = COOKIE_SALT;

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'cache'			=> MODPATH . 'cache',		// Cache manager
	'database'		=> MODPATH . 'database',	// Database access
	'auth'			=> MODPATH . 'auth',		// Basic authentication
	'orm'			=> MODPATH . 'orm',			// Object Relationship Mapping,
	'pagination'	=> MODPATH . 'pagination',
	'minion'		=> MODPATH . 'minion',		// Minion
	'plugins'		=> MODPATH . 'plugins',
	'userguide'		=> MODPATH . 'userguide',	// User guide and API documentation,
	'bootstrap'		=> MODPATH . 'bootstrap',
	'breadcrumbs'	=> MODPATH . 'breadcrumbs',
	'api'			=> MODPATH . 'api',
	'debug_toolbar'	=> MODPATH . 'debug_toolbar', // Kohana Debug Toolbar http://brotkin.ru/
	'sheduler'		=> MODPATH . 'sheduler',
	'snippet'		=> MODPATH . 'snippet',
) );

// Init settings
Setting::init();
Behavior::init();

Route::set( 'user', ADMIN_DIR_NAME.'/<action>(?next=<next_url>)', array(
	'action' => '(login|logout|forgot|register)',
) )
	->defaults( array(
		'controller' => 'login',
	) );

Route::set( 'templates', ADMIN_DIR_NAME.'/(<controller>(/<action>(/<id>)))', array(
	'controller' => '(layout|snippet)',
	'id' => '.*'
) )
	->defaults( array(
		'controller' => 'index',
		'action' => 'index',
	) );

Route::set( 'downloader', '('.ADMIN_DIR_NAME.'/)download/<path>', array(
	'path' => '.*'
) )
	->defaults( array(
		'directory' => 'system',
		'controller' => 'download',
		'action' => 'index',
	) );

Route::set( 'admin', ADMIN_DIR_NAME.'(/<controller>(/<action>(/<id>)))')
	->defaults( array(
		'controller' => Setting::get('default_tab'),
		'action' => 'index',
	) );

// Системные контроллеры
Route::set( 'system', '<directory>-<controller>-<action>(/<id>)', array(
	'directory' => '(ajax|action|form)',
	'controller' => '[A-Za-z\_]+',
	'action' => '[A-Za-z\_]+',
	'id' => '.+',
) );

Route::set( 'default', '(<page>)(<suffix>)' , array(
	'page' => '.*',
	'suffix' => URL_SUFFIX
) )
	->defaults( array(
		'controller' => 'front',
		'action' => 'index',
		'suffix' => URL_SUFFIX
	) );
