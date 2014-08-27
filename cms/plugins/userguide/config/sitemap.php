<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Documentation',
		'icon' => 'book',
		'children' => array(
			array(
				'name' => 'User Guide', 
				'url' => Route::get('backend')->uri(array('controller' => 'guide', 'action' => 'doc')),
				'priority' => 101,
				'icon' => 'book',
				'hotkeys' => 'f1'
			),
			array(
				'name' => 'API Browser', 
				'url' => Route::get('backend')->uri(array('controller' => 'guide', 'action' => 'api')),
				'priority' => 102,
				'icon' => 'flask',
				'hotkeys' => 'f2'
			)
		)
	)
);
