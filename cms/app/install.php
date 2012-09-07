<?php

// Add a suffix to pages (simluating static pages '.html')
define('URL_SUFFIX', '.html');

define('DB_TYPE', 'mysql');
define('DB_SERVER', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('TABLE_PREFIX', '');


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'database'		=> MODPATH . 'database', // Database access
	'auth'			=> MODPATH . 'auth', // Basic authentication
	'orm'			=> MODPATH . 'orm', // Object Relationship Mapping,
) );

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

Route::set( 'install', 'install(/<action>(/<id>))' )
	->defaults( array(
		'directory' => 'system',
		'controller' => 'install',
		'action' => 'index',
	) );