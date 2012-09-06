<?php defined('SYSPATH') or die('No direct access allowed.');

// CMS defaults
define('BASE_URL',				'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/');
define('ADMIN_URL',				BASE_URL.ADMIN_DIR_NAME.'/');
define('PLUGINS_URL',			BASE_URL . 'cms/plugins/');

define('PUBLICPATH',			DOCROOT.'pulic'.DIRECTORY_SEPARATOR);
define('PUBLIC_URL',			BASE_URL.'pulic/');

define('LAYOUTS_SYSPATH',		DOCROOT . 'layouts' . DIRECTORY_SEPARATOR);
define('SNIPPETS_SYSPATH',		DOCROOT . 'snippets' . DIRECTORY_SEPARATOR);

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}

/**
 * Display a 404 page not found and exit
 */
function page_not_found()
{
    Observer::notify('page_not_found');
	echo View::factory('layouts/404');
    exit;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set( 'Europe/Moscow' );

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale( LC_ALL, 'ru_RU.utf-8' );

/**
 * Enable the Frog auto-loader.
 *
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register( array('Kohana', 'auto_load') );


/**
 * Enable the Frog auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set( 'unserialize_callback_func', 'spl_autoload_call' );

/**
 * InitializeCore, setting the default options.
 *
 * The following options are available:
 *
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */

Kohana::init( array(
	'base_url'			=> '/',
	'index_file'		=> FALSE,
	'caching'			=> FALSE,
	'errors'			=> TRUE,
	'profile'			=> TRUE
) );

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'database'		=> MODPATH . 'database', // Database access
	'auth'			=> MODPATH . 'auth', // Basic authentication
	'orm'			=> MODPATH . 'orm', // Object Relationship Mapping,
	'cache'			=> MODPATH . 'cache', // Object Relationship Mapping
) );


define('IS_BACKEND', URL::math('/admin', $_SERVER['REQUEST_URI']));

/**
 * Set default cookie salt
 */
Cookie::$salt = 'AS7hjdd4234fdsdsfAD';

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach( new Config_File );

// Init settings
Setting::init();

// Init plugins
Plugins::init();

I18n::lang( 'ru' );

if ( ! Route::cache() )
{
	Route::set( 'error', 'system/error(/<message>)', array(
		'message' => '.+',
	) )
	->defaults( array(
		'directory' => 'system',
		'controller' => 'error',
	) );
	
	// Системные контроллеры
	Route::set( 'system', '<directory>-<controller>-<action>(/<id>)', array(
		'directory' => '(ajax|form)',
		'controller' => '[A-Za-z\_]+',
		'action' => '[A-Za-z\_]+',
		'id' => '.+',
	) )
		->defaults( array(
			'directory' => 'action',
		) );

	Route::set( 'user', 'admin/<action>(?next=<next_url>)', array(
		'action' => '(login|logout|forgot|register)',
	) )
		->defaults( array(
			'controller' => 'login',
		) );

	Route::set( 'plugin', 'admin/plugin/(<controller>(/<action>(/<id>)))', array(
		'id' => '.*'
	) )
		->defaults( array(
			'controller' => 'index',
			'action' => 'index',
		) );

	Route::set( 'templates', 'admin/(<controller>(/<action>(/<id>)))', array(
		'controller' => '(layout|snippet)',
		'id' => '.*'
	) )
		->defaults( array(
			'controller' => 'index',
			'action' => 'index',
		) );

	Route::set( 'plugins', 'admin/(<controller>(/<action>(/<id>)))', array(
		'controller' => 'plugins',
		'id' => '.*'
	) )
		->defaults( array(
			'controller' => 'plugins',
			'action' => 'index',
		) );

	Route::set( 'admin', 'admin/(<controller>(/<action>(/<id>)))' )
		->defaults( array(
			'controller' => Setting::get('default_tab'),
			'action' => 'index',
		) );

	Route::set( 'default', '(<page>)(<suffix>)' , array(
		'page' => '.*',
		'suffix' => URL_SUFFIX
	) )
		->defaults( array(
			'controller' => 'front',
			'action' => 'index',
		) );
	
	Route::cache(TRUE);
}