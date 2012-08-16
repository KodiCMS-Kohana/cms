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

// Try connect to DB
if (class_exists('PDO'))
	$connection = new PDO(
		DB_DSN,
		DB_USER,
		DB_PASS
	);
else
	throw new Core_Exception('CMS need PHP PDO extension for working with DB!');

switch( $connection->getAttribute(PDO::ATTR_DRIVER_NAME) )
{
	case 'mysql':
		$connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		$connection->exec('SET time_zone = "'. date_default_timezone_get() .'"');
		$connection->exec('SET NAMES "utf8"');
		break;
	default:
		throw new Core_Exception('CMS work only with MySQL databases!');
		break;
}

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

Record::connection( $connection );

AuthUser::load();

I18n::lang( AuthUser::isLoggedIn() ? AuthUser::getRecord()->language  :  'ru' );

echo Request::factory()
	->execute()
	->send_headers()
	->body();