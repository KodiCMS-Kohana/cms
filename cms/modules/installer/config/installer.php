<?php defined('SYSPATH') or die('No direct access allowed.');

$database_drivers = array();

if (extension_loaded('mysqli'))
{
	$database_drivers['mysqli'] = __('MySQLi');
}

if (version_compare(PHP_VERSION, '5.5', '<'))
{
	$database_drivers['mysql'] = __('MySQL');
}

$cache_types = array();
if (extension_loaded('apc'))
{
	$cache_types['apc'] = __('APC Cache');
}

if (class_exists('MongoClient'))
{
	$cache_types['mongodb'] = __('MongoDB');
}

if (class_exists('PDO') AND extension_loaded('pdo'))
{
	$cache_types['sqlite'] = __('SQLite cache');
	$database_drivers['pdo'] = __('PDO');
}

if (extension_loaded('memcache'))
{
	$cache_types['memcachetag'] = __('Memcache');
}

$cache_types['file'] = __('File cache');

return array(
	'cache_types' => $cache_types,
	'session_types' => array(
		'native' => __('Native'), 
		'database' => __('Database'), 
		'cookie' => __('Cookie')
	),
	'database_drivers' => $database_drivers,
	'default_config' => array(
		'site' => array(
			'allow_html_title' => Config::NO,
			'breadcrumbs' => Config::YES,
			'debug' => Config::NO,
			'default_filter_id' => 'redactor',
			'default_status_id' => 100,
			'description' => '',
			'find_similar' => Config::YES,
			'profiling' => Config::NO,
		),
		'api' => array(
			'mode' => Config::NO
		),
	)
);