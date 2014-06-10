<?php defined('SYSPATH') or die('No direct access allowed.');

switch(DB_TYPE)
{
	case 'mysql':
		$server = DB_SERVER;
		if(defined('DB_PORT')) $server .= ':' . DB_PORT;
		$config = array(
			'type'       => DB_TYPE,
			'connection' => array(
				'hostname'   => $server,
				'database'   => DB_NAME,
				'username'   => DB_USER,
				'password'   => DB_PASS,
				'persistent' => FALSE,
			),
			'table_prefix' => TABLE_PREFIX,
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'	   => TRUE
		);
		
		break;
	case 'mysqli':
		$config = array(
			'type'       => DB_TYPE,
			'connection' => array(
				'hostname'   => DB_SERVER,
				'port'		 => defined('DB_PORT') ? DB_PORT : NULL,
				'database'   => DB_NAME,
				'username'   => DB_USER,
				'password'   => DB_PASS,
				'persistent' => FALSE,
			),
			'table_prefix' => TABLE_PREFIX,
			'charset'      => 'utf8',
			'caching'      => FALSE,
			'profiling'	   => TRUE
		);
		
		break;
	case 'pdo':
		$config = array(
			'type'       => 'PDO',
			'connection' => array(
				'dsn'        => 'mysql:host='.DB_SERVER.';dbname='.DB_NAME,
				'username'   => DB_USER,
				'password'   => DB_PASS,
				'persistent' => FALSE,
			),
			'table_prefix' => TABLE_PREFIX,
			'charset'      => 'utf8',
			'caching'      => FALSE,
		);
		
		break;
	default:
		throw new Kohana_Exception('Database driver :driver not supported',
			array(':driver' => DB_TYPE));
}

return array('default' => $config);