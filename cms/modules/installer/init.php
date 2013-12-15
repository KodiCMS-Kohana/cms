<?php defined('SYSPATH') or die('No direct script access.');

define('INSTALL_DATA', MODPATH . 'installer' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);

try 
{
	date_default_timezone_get();
} 
catch (Exception $e) 
{
	date_default_timezone_set('UTC');
}

Database::$default = 'install';

Route::set( 'install', 'install(/<action>(/<id>))' )
	->defaults( array(
		'controller' => 'install',
		'action' => 'error',
	) );