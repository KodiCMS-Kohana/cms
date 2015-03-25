<?php defined('SYSPATH') or die('No direct script access.');
return array
(
	'front_page' => Date::DAY,
	
	'file'    => array(
		'driver'             => 'File',
		'cache_dir'          =>  CMSPATH . 'cache',
		'default_expire'     => 3600,
		'ignore_on_delete'   => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	'sqlite'   => array(
		'driver'             => 'Sqlite',
		'default_expire'     => 3600,
		'database'           => CMSPATH.'cache/kohana-cache.sql3',
		'schema'             => 'CREATE TABLE caches(id VARCHAR(127) PRIMARY KEY, tags VARCHAR(255), expiration INTEGER, cache TEXT)',
	),
	'apc'      => array(
		'driver'             => 'Apc',
		'default_expire'     => 3600,
	),
	'memcachetag' => array(
		'driver'             => 'MemcacheTag',
		'default_expire'     => 3600,
		'compression'        => FALSE,              // Use Zlib compression (can cause issues with integers)
		'servers'            => array(
			'local' => array(
				'host'             => 'localhost',  // Memcache Server
				'port'             => 11211,        // Memcache port number
				'persistent'       => FALSE,        // Persistent connection
				'weight'           => 1,
				'timeout'          => 1,
				'retry_interval'   => 15,
				'status'           => TRUE,
			),
		),
		'instant_death'      => TRUE,
	),
	
	'mongodb'      => array(
		'driver'             => 'MongoDB',
		'host'				 => 'localhost',  // Memcache Server
		'port'				 => 27017,        // Memcache port number
		'default_expire'     => 3600,
		'database'           => 'kodicms-cache',
		'collection'		 => 'default'
	),
);
