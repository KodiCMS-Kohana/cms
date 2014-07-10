<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'volumes' => array(
		// Local volume
		array(
			'driver'        => 'LocalFileSystem',	// driver for accessing file system (REQUIRED)
			'path'          => substr(PUBLICPATH, 0, -1),			// path to files (REQUIRED)
			'URL'           => PUBLIC_URL,			// URL to files (REQUIRED),
			'rootAlias'     => __('Public'),
			'uploadMaxSize'	=> '10M',
			'mimeDetect'	=> 'internal',
			'imgLib'		=> 'gd',

		),
		// MySQL volume
//		array(
//			'driver'        => 'MySQL',
//			'host'			=> DB_SERVER,
//			'user'          => DB_USER,
//			'pass'          => DB_PASS,
//			'db'            => DB_NAME,
//			'port'          => DB_PORT,
//			'files_table'   => 'elfinder_file',
//			'path'			=> 1,
//			'tmbPath'		=> substr(PUBLICPATH, 0, -1),
//			'tmpPath'		=> TMPPATH
//		)
	)
);