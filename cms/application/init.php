<?php defined('SYSPATH') or die('No direct access allowed.');

if (PHP_SAPI != 'cli')
{
	define('IS_BACKEND', URL::match(ADMIN_DIR_NAME, Request::detect_uri()));
}

if (!defined('IS_BACKEND'))
{
	define('IS_BACKEND', FALSE);
}

if (!defined('SESSION_TYPE'))
{
	define('SESSION_TYPE', 'native');
}

// CMS defaults
define('ADMIN_URL',		BASE_URL . ADMIN_DIR_NAME . '/');
define('PLUGINS_URL',	BASE_URL . 'cms/plugins/');
define('PUBLIC_URL',	BASE_URL . 'public/');

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set( DEFAULT_TIMEZONE );

/**
 * Cookie Salt
 * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
 * 
 * If you have not defined a cookie salt in your Cookie class then
 * uncomment the line below and define a preferrably long salt.
 */
Cookie::$salt = COOKIE_SALT;

/**
 * Set the default session type
 */
Session::$default = SESSION_TYPE;

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules( array(
	'users'			=> CMS_MODPATH . 'users',
	'kodicms'		=> CMS_MODPATH . 'kodicms',
	'assets'		=> CMS_MODPATH . 'assets',		// Asset Manager
	'logs'			=> CMS_MODPATH . 'logs',
	
	'auth'			=> CMS_MODPATH . 'auth',	// Basic authentication
	'oauth'			=> CMS_MODPATH . 'oauth',
	
	'orm'			=> MODPATH . 'orm',			// Object Relationship Mapping
	'minion'		=> MODPATH . 'minion',		// Minion
	'cache'			=> MODPATH . 'cache',		// Cache manager
	'database'		=> MODPATH . 'database',	// Database access
	'image'			=> MODPATH . 'image',

	'pagination'	=> CMS_MODPATH . 'pagination',
	'email'			=> CMS_MODPATH . 'email',
	'email_queue'	=> CMS_MODPATH . 'email_queue',
	'filesystem'	=> CMS_MODPATH . 'filesystem',
	'scheduler'		=> CMS_MODPATH . 'scheduler',
	'snippet'		=> CMS_MODPATH . 'snippet',
	'pages'			=> CMS_MODPATH . 'pages',		// Pages
	'page_parts'	=> CMS_MODPATH . 'page_parts',	// Page parts
	'tags'			=> CMS_MODPATH . 'tags',		// Tags
	'widget'		=> CMS_MODPATH . 'widget',
	'reflinks'		=> CMS_MODPATH . 'reflinks',
	'elfinder'		=> CMS_MODPATH . 'elfinder',
	'api'			=> CMS_MODPATH . 'api',
	'navigation'	=> CMS_MODPATH . 'navigation',
	'breadcrumbs'	=> CMS_MODPATH . 'breadcrumbs',
	'behavior'		=> CMS_MODPATH . 'behavior',
	'plugins'		=> CMS_MODPATH . 'plugins',
	'datasource'	=> CMS_MODPATH . 'datasource',
	'search'		=> CMS_MODPATH . 'search',
	'sidebar'		=> CMS_MODPATH . 'sidebar',
	'update'		=> CMS_MODPATH . 'update',
	'captcha'		=> CMS_MODPATH . 'captcha',
	'dashboard'		=> CMS_MODPATH . 'dashboard'
) );

Kohana::$config->attach(new Config_Database);

Observer::notify('modules::after_load');

Kohana::$log->attach(new Log_Database('logs'));

Route::set('user', ADMIN_DIR_NAME . '/<action>(?next=<next_url>)', array(
	'action' => '(login|logout|forgot)',
))
->defaults(array(
	'controller' => 'login',
));

Route::set('templates', ADMIN_DIR_NAME . '/(<controller>(/<action>(/<id>)))', array(
	'controller' => '(layout|snippet)',
	'id' => '.*'
))
->defaults(array(
	'controller' => 'index',
	'action' => 'index',
));

Route::set('downloader', '(' . ADMIN_DIR_NAME . '/)download/<path>', array(
	'path' => '.*'
))
->defaults(array(
	'directory' => 'system',
	'controller' => 'download',
	'action' => 'index',
));

Route::set('backend', ADMIN_DIR_NAME . '(/<controller>(/<action>(/<id>)))')
->defaults(array(
	'controller' => 'dashboard',
	'action' => 'index',
));

Route::set('system', '<directory>-<controller>-<action>(/<id>)', array(
	'directory' => '(ajax|action|form)',
	'controller' => '[A-Za-z\_]+',
	'action' => '[A-Za-z\_]+',
	'id' => '.+',
));

Route::set('default', '(<page>)(<suffix>)', array(
	'page' => '.*',
	'suffix' => URL_SUFFIX
))
->defaults(array(
	'controller' => 'front',
	'action' => 'index',
	'suffix' => URL_SUFFIX
));

Observer::notify('system::init');
