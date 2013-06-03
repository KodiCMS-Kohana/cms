<?php defined('SYSPATH') or die('No direct access allowed.');

return array
	(
	'default' => array(
		'type'       => DB_TYPE,
		'connection' => array(
			'hostname'   => DB_SERVER,
			'database'   => DB_NAME,
			'username'   => DB_USER,
			'password'   => DB_PASS,
			'persistent' => FALSE,
		),
		'table_prefix' => TABLE_PREFIX,
		'charset'      => 'utf8',
		'caching'      => FALSE,
		'profiling'	   => TRUE
	)
);