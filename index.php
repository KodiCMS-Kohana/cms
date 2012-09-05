<?php

// Base pathes
define('SYSPATH',	dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('APPPATH',	SYSPATH . 'cms' . DIRECTORY_SEPARATOR);
define('MODPATH',	APPPATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);

define('EXT', '.php');

// Include cofig
$config_file = SYSPATH.'config'.EXT;
define('INSTALLED',	file_exists($config_file));

if (INSTALLED)
	require_once($config_file);

/**
 * Define the start time of the application, used for profiling.
 */
if ( ! defined('FROG_START_TIME'))
{
	define('FROG_START_TIME', microtime(TRUE));
}

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
if ( ! defined('FROG_START_MEMORY'))
{
	define('FROG_START_MEMORY', memory_get_usage());
}

// Bootstrap the application
require APPPATH.'bootstrap'.EXT;

AuthUser::load();

I18n::lang( AuthUser::isLoggedIn() ? AuthUser::getRecord()->language  :  'ru' );

echo Request::factory()
	->execute()
	->send_headers()
	->body();