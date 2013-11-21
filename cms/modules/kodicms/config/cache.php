<?php defined('SYSPATH') or die('No direct script access.');
return array
(
	'page_parts' => Date::DAY,
	'front_page' => Date::DAY,
	'tags' => Date::DAY,
	
	'file'    => array(
		'driver'             => 'file',
		'cache_dir'          =>  CMSPATH.'cache',
		'default_expire'     => 3600,
		'ignore_on_delete'   => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	'sqlite'   => array(
		'driver'             => 'sqlite',
		'default_expire'     => 3600,
		'database'           => CMSPATH.'cache/kohana-cache.sql3',
		'schema'             => 'CREATE TABLE caches(id VARCHAR(127) PRIMARY KEY, tags VARCHAR(255), expiration INTEGER, cache TEXT)',
	),
);
