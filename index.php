<?php

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Frog CMS.
 *
 * Frog CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Frog CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Frog CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * The Framework file is a modified version of the so-called Green Framework.
 * 
 * @package framework
 *
 * @author Maslakov Alexander <jmas.ukraine@gmail.com>
 * @version 0.1.0
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

// CMS version
define('CMS_VERSION', '0.1.4');

// Base pathes
define('CMS_ROOT',          dirname(__FILE__));
define('CORE_DIR_NAME',     'cms');
define('CORE_ROOT',         CMS_ROOT.DIRECTORY_SEPARATOR.CORE_DIR_NAME);


// Catching errors
register_shutdown_function('cms_shutdown_handler');
set_error_handler('cms_error_handler');


// Include cofig
$config_file = CMS_ROOT.DIRECTORY_SEPARATOR.'config.php';

if (file_exists($config_file))
	require_once($config_file);
else
{
	header('Location: install/');
	exit();
}


// Require Framework
// Defines: DEBUG, APP_PATH, HELPER_PATH, BASE_URL, DEFAULT_CONTROLLER, DEFAULT_ACTION, DEFAULT_TIMEZONE
require_once(CORE_ROOT.DIRECTORY_SEPARATOR.'Framework.php');


// CMS defaults
if (!defined('PLUGINS_DIR_NAME'))   define('PLUGINS_DIR_NAME', 'plugins');
if (!defined('PLUGINS_ROOT'))       define('PLUGINS_ROOT',     CORE_ROOT.DIRECTORY_SEPARATOR.PLUGINS_DIR_NAME);
if (!defined('PLUGINS_URL'))        define('PLUGINS_URL',      BASE_URL.CORE_DIR_NAME.'/'.PLUGINS_DIR_NAME.'/');

if (!defined('PUBLIC_DIR_NAME'))   define('PUBLIC_DIR_NAME',   'public');
if (!defined('PUBLIC_ROOT'))       define('PUBLIC_ROOT',       CMS_ROOT.DIRECTORY_SEPARATOR.PUBLIC_DIR_NAME);
if (!defined('PUBLIC_URL'))        define('PUBLIC_URL',        BASE_URL.PUBLIC_DIR_NAME.'/');

if (!defined('LAYOUTS_DIR_NAME'))  define('LAYOUTS_DIR_NAME',  'layouts');
if (!defined('LAYOUTS_ROOT'))      define('LAYOUTS_ROOT',      CMS_ROOT.DIRECTORY_SEPARATOR.LAYOUTS_DIR_NAME);
if (!defined('LAYOUTS_EXT'))       define('LAYOUTS_EXT',       'php');

if (!defined('SNIPPETS_DIR_NAME')) define('SNIPPETS_DIR_NAME', 'snippets');
if (!defined('SNIPPETS_ROOT'))     define('SNIPPETS_ROOT',     CMS_ROOT.DIRECTORY_SEPARATOR.SNIPPETS_DIR_NAME);
if (!defined('SNIPPETS_EXT'))      define('SNIPPETS_EXT',      'php');

if (!defined('URL_SUFFIX'))        define('URL_SUFFIX',         '.html');
if (!defined('ADMIN_DIR_NAME'))    define('ADMIN_DIR_NAME',     'admin');
if (!defined('USE_MOD_REWRITE'))   define('USE_MOD_REWRITE',    false);
if (!defined('CMS_URL'))           define('CMS_URL',            BASE_URL . (USE_MOD_REWRITE ? '' : '?/'));
if (!defined('DEFAULT_LOCALE'))    define('DEFAULT_LOCALE',     'en');


// Try connect to DB
if (class_exists('PDO'))
	$connection = new PDO(
		DB_DSN,
		DB_USER,
		DB_PASS
	);
else
	throw new Exception('CMS need PHP PDO extension for working with DB!');

switch( $connection->getAttribute(PDO::ATTR_DRIVER_NAME) )
{
	case 'mysql':
		$connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		$connection->exec('SET time_zone = "'. date_default_timezone_get() .'"');
		$connection->exec('SET NAMES "utf8"');
		break;
	
	case 'sqlite':
		if ( !function_exists( 'mysql_date_format_function' ) )
		{
			function mysql_function_date_format( $date, $format )
			{
				return strftime( $format, strtotime( $date ) );
			}
		}
		
		// this function require mb_string extension (note this!)
		function mysql_lower_function( $str )
		{
			return mb_strtolower( $str, 'UTF-8' );
		}
		
		// this function require mb_string extension (note this!)
		function mysql_now_function()
		{
			return date('Y-m-d H:i:s');
		}
		
		$connection->sqliteCreateFunction('lower', 'mysql_lower_function', 1);
		$connection->sqliteCreateFunction('now', 'mysql_now_function', 0);
		$connection->sqliteCreateFunction('date_format', 'mysql_function_date_format', 2);
		break;
	
	default:
		throw new Exception('CMS work only with MySQL and SQLite3 databases!');
		break;
}

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

Record::connection( $connection );


// JSON
if (!function_exists('json_encode'))
{
	use_helper('JSON');
}


// Parse URI
$uri = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING']: '';

if ($uri != '' && $uri[0] == '/')
{
	$uri = urldecode($uri);
	
	if (strstr($uri, '?') !== false)
		$uri = substr($uri, 0, strpos($uri, '?'));
	
	if (strstr($uri, '&') !== false)
		$uri = substr($uri, 0, strpos($uri, '&'));
	
	if (URL_SUFFIX != '' && URL_SUFFIX != '/' && strstr($uri, URL_SUFFIX) !== false)
		$uri = substr($uri, 0, strrpos($uri, URL_SUFFIX));
}
else
	$uri = '/';

define('CURRENT_URI', $uri);


// Init libraries
Setting::init();
Flash::init();
AuthUser::load();


// Set system localization
use_helper('I18n');
I18n::setLocale( AuthUser::isLoggedIn() ? AuthUser::getRecord()->language  :  DEFAULT_LOCALE );


// Init plugins
Plugin::init();


// Routes
if (strpos(CURRENT_URI, ADMIN_DIR_NAME) === 1)
{
	$default_tab = Setting::get('default_tab');
	$default_tab = (empty($default_tab) ? 'page/index' : $default_tab);
	
	Dispatcher::addRoute(array(
		'/'.ADMIN_DIR_NAME         => $default_tab,
		'/'.ADMIN_DIR_NAME.'/'     => $default_tab,
		'/'.ADMIN_DIR_NAME.'/:any' => '$1'
	));
}
else
{	
	Dispatcher::addRoute(array(
		'/'     => 'front/index',
		'/:any' => 'front/index'
	));
}

// Run
Dispatcher::dispatch(CURRENT_URI);


/**
 * Create a really nice url like http://www.example.com/controller/action/params#anchor
 *
 * you can put as many params as you want,
 * if a params start with # it is considered to be an Anchor
 *
 * get_url('controller/action/param1/param2') // I always use this method
 * get_url('controller', 'action', 'param1', 'param2');
 *
 * @param string conrtoller, action, param and/or #anchor
 * @return string
 */
function get_url()
{
	$is_backend = (strpos(CURRENT_URI, ADMIN_DIR_NAME) === 1 ? true : false);
	
	$params = func_get_args();
	if( count($params) === 0 ) return CMS_URL. ($is_backend ? ADMIN_DIR_NAME.'/' : '');
	if( count($params) === 1 ) return CMS_URL. ($is_backend ? ADMIN_DIR_NAME.'/' : '') . $params[0] . ($is_backend ? '' : (strstr($params[0], '.') !== false ? '' : URL_SUFFIX));
	
	$url = '';
	
	foreach( $params as $param )
	{
		if( strlen($param) )
			$url .= $param{0} == '#' ? $param: '/'. $param;
	}
	
	return CMS_URL . ($is_backend ? ADMIN_DIR_NAME.'/' : '') . rtrim($url, '/') . ($is_backend ? '' : URL_SUFFIX);
}


/**
* Handler for register_shutdown_function (Fatal errors catching)
*/
function cms_shutdown_handler()
{
	if (($error = error_get_last()) !== null)
	{
		cms_error_handler($error['type'], $error['message'], $error['file'], $error['line']);
	}
}


/**
* Handler for set_error_handler (Fatal errors catching)
*/
function cms_error_handler( $type, $message, $file, $line )
{
	if ( DEBUG === true )
	{
		switch ($type)
		{
			case E_ERROR:
			case E_USER_ERROR:
				$color = 'red';
				$type_name = 'Run-time error';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$color = 'orange';
				$type_name = 'Warning';
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$color = 'yellow';
				$type_name = 'Notice';
				break;
			default:
				$color = 'pink';
				$type_name = 'Unspecified error';
				break;
		}
		
		echo('<!--### ERROR '.$message.' '.$file.' '.$line.' ###-->'
		    .'<!--">--></textarea></form></title></comment></a></div></span></ilayer></layer></div></iframe></noframes></style></noscript></table></script></applet></font>'
		    .'<div style="position:relative;font-family:Verdana !important; font-size:12px !important; background:#fff; border:1px solid '.$color.' !important; color:#000 !important; text-align:left !important; margin:1em 0 !important; clear:both; z-index:10000 !important; overflow:hidden;">'
		    .'<h1 style="font-size:130%; font-weight:bolder; padding:5px 10px; background:'. $color .' !important; margin:0;">'. $type_name .' (<a href="http://www.php.net/manual/ru/errorfunc.constants.php" target="_blank">#'. $type .'</a>): '.$message.'</h1>'
		    .'<div style="font-size:110%; padding:5px 10px;">'
		    .'<p><b>File:</b> '.$file.'</p>'
		    .'<p><b>Line:</b> '.$line.'</p>'
		    .'</div>'
		    .'</div>'
		    .'<!--### END ERROR ###-->');
	}
	
	if (class_exists('Observer'))
		Observer::notify('cms_error', array($type, $message, $file, $line));
	
	if ($type == E_ERROR || $type == E_USER_ERROR)
		exit;
} // end cms_error_handler