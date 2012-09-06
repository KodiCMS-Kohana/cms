<?php


/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'database'		=> MODPATH . 'database', // Database access
	'auth'			=> MODPATH . 'auth', // Basic authentication
	'orm'			=> MODPATH . 'orm', // Object Relationship Mapping,
	'cache'			=> MODPATH . 'cache', // Object Relationship Mapping
) );

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

// Init settings
Setting::init();

// Init plugins
Plugins::init();

I18n::lang( 'ru' );

Route::set( 'user', ADMIN_DIR_NAME.'/<action>(?next=<next_url>)', array(
	'action' => '(login|logout|forgot|register)',
) )
	->defaults( array(
		'controller' => 'login',
	) );

Route::set( 'plugin', ADMIN_DIR_NAME.'/plugin/(<controller>(/<action>(/<id>)))', array(
	'id' => '.*'
) )
	->defaults( array(
		'controller' => 'index',
		'action' => 'index',
	) );

Route::set( 'templates', ADMIN_DIR_NAME.'/(<controller>(/<action>(/<id>)))', array(
	'controller' => '(layout|snippet)',
	'id' => '.*'
) )
	->defaults( array(
		'controller' => 'index',
		'action' => 'index',
	) );

Route::set( 'plugins', ADMIN_DIR_NAME.'/(<controller>(/<action>(/<id>)))', array(
	'controller' => 'plugins',
	'id' => '.*'
) )
	->defaults( array(
		'controller' => 'plugins',
		'action' => 'index',
	) );

Route::set( 'admin', ADMIN_DIR_NAME.'/(<controller>(/<action>(/<id>)))' )
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