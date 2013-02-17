<?php defined('SYSPATH') or die('No direct script access.');

define('INSTALL_DATA', MODPATH . 'installer' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);

Route::set( 'install', 'install(/<action>(/<id>))' )
	->defaults( array(
		'controller' => 'install',
		'action' => 'index',
	) );