<?php defined('SYSPATH') or die('No direct access allowed.');

// CMS defaults
define('BASE_URL',				'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/');

define('ADMIN_DIR_NAME',		'admin');
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
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */

if ( isset( $_SERVER['KOHANA_ENV'] ) )
{
	Kohana::$environment = constant( 'Kohana::' . strtoupper( $_SERVER['KOHANA_ENV'] ) );
}
else
{
	//Kohana::$environment = Kohana::PRODUCTION;
}

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
	'caching'			=> Kohana::$environment === Kohana::PRODUCTION,
	'profile'			=> Kohana::$environment !== Kohana::PRODUCTION,
	'errors'			=> TRUE
) );


/**
 * Set default cookie salt
 */
Cookie::$salt = 'AS7hjdd4234fdsdsfAD';

I18n::lang('ru');

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);


Route::set( 'error', 'system/error(/<code>(/<message>))', array(
		'message' => '.*',
		'code' => '[0-9]+'
	) )
	->defaults( array(
		'directory' => 'system',
		'controller' => 'error',
		'action' => 'index'
	) );