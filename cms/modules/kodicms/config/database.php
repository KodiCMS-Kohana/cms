<?php defined('SYSPATH') or die('No direct access allowed.');

 if (!defined('DB_DRIVER'))
{
	define('DB_DRIVER', DB_TYPE);
}

switch (DB_DRIVER)
{
	case 'mysql':
		$server = DB_SERVER;
		if (defined('DB_PORT'))
		{
			$server .= ':' . DB_PORT;
		}
		
		$config = array(
			'type'       => 'MySQL',
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
			'type'       => 'MySQLi',
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
	case 'pdo::mysql':
		$config = array(
			'type'       => 'PDO',
			'connection' => array(
				'dsn'        => 'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME,
				'username'   => DB_USER,
				'password'   => DB_PASS,
				'persistent' => FALSE,
			),
			'table_prefix' => TABLE_PREFIX,
			'charset'      => 'utf8',
			'caching'      => FALSE,
		);
		
		break;
	case 'pdo::sqlite':
		$config = array(
			'type'       => 'PDO_SQLite',
			'connection' => array(
				'dsn'        => 'sqlite:' . CMSPATH . 'db' . DIRECTORY_SEPARATOR . '.' . DB_NAME . '.sqlite',
				'username'   => NULL,
				'password'   => NULL,
				'persistent' => FALSE,
			),
			'table_prefix' => TABLE_PREFIX,
			'charset'      => NULL,
			'caching'      => FALSE,
		);
		
		break;
	default:
		throw new Kohana_Exception('Database driver :driver not supported',
			array(':driver' => DB_DRIVER));
}

return array('default' => $config);