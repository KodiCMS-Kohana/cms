<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'cache_types' => array(
		'file' => __('File cache'), 
		'sqlite' => __('SQLite cache'), 
		'apc' => __('APC Cache'),
		'mongodb' => __('MongoDB'),
		'memcachetag' => __('Memcache'),
	),
	
	'default_config' => array(
		'site' => array(
			'allow_html_title' => Config::NO,
			'breadcrumbs' => Config::YES,
			'date_format' => 'd F Y',
			'debug' => Config::NO,
			'default_filter_id' => 'redactor',
			'default_status_id' => 100,
			'default_tab' => 'page',
			'description' => '',
			'find_similar' => Config::YES,
			'profiling' => Config::NO,
		),
		'api' => array(
			'mode' => Config::NO
		),
	),
	'default_plugins' => array(
		'archive', 'maintenance', 'page_not_found', 'redactor', 'slug_translit'
	)
);