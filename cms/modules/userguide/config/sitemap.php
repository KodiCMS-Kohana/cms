<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Documentation',
		'children' => array(
			array(
				'name' => 'User Guide', 
				'url' => Route::url('backend', array('controller' => 'guide', 'action' => 'doc')),
				'priority' => 101,
				'icon' => 'book',
				'hotkeys' => 'f1'
			),
			array(
				'name' => 'API Browser', 
				'url' => Route::url('backend', array('controller' => 'guide', 'action' => 'api')),
				'priority' => 102,
				'icon' => 'beaker',
				'hotkeys' => 'f2'
			)
		)
	)
);
