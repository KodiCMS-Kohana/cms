<?php defined('SYSPATH') or die('No direct access allowed.');

// В данном конфиге показан пример подключения в файловом менеджере дополнительной папки
// с файлами
// Примеры коннекторов https://github.com/Studio-42/elFinder/blob/2.x/php/connector.php#L243
return array(
	'volumes' => array(
		'skeleton_public' => array(
			'driver'			=> elFinder_Connector::FILE_SYSTEM,		// driver for accessing file system (REQUIRED)
			'path'				=> substr(PLUGIN_SKELETON_PATH, 0, -1),	// path to files (REQUIRED)
			'URL'				=> PLUGIN_SKELETON_URL,				// URL to files (REQUIRED),
			'alias'				=> __('Skeleton public'),
			'uploadMaxSize'		=> '10M',
			'mimeDetect'		=> 'internal',
			'imgLib'			=> 'gd',
		)
	)
);