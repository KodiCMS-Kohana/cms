<?php defined('SYSPATH') or die('No direct access allowed.');

// CMS defaults
define('BASE_URL',			'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/');
define('ADMIN_RESOURCES',	BASE_URL.'admin/');
define('ADMIN_URL',			BASE_URL.ADMIN_DIR_NAME.'/');
define('PLUGINS_URL',		BASE_URL . 'cms/plugins/');
define('PUBLICPATH',		DOCROOT.'public'.DIRECTORY_SEPARATOR);
define('PUBLIC_URL',		BASE_URL.'public/');
define('LAYOUTS_SYSPATH',	DOCROOT . 'layouts' . DIRECTORY_SEPARATOR);
define('SNIPPETS_SYSPATH',	DOCROOT . 'snippets' . DIRECTORY_SEPARATOR);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'database'		=> MODPATH . 'database', // Database access
	'auth'			=> MODPATH . 'auth', // Basic authentication
	'orm'			=> MODPATH . 'orm', // Object Relationship Mapping,
	'cache'			=> MODPATH . 'cache', // Object Relationship Mapping,
	'pagination'	=> MODPATH . 'pagination',
) );

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

Route::set( 'admin', ADMIN_DIR_NAME.'(/(<controller>(/<action>(/<id>))))')
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