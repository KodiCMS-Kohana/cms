<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Plugins',
		'divider' => TRUE,
		'icon' => 'puzzle-piece',
		'priority' => 9000,
		'children' => array(
			array(
				'name' => 'List',
				'url' => Route::get('backend')->uri(array('controller' => 'plugins')),
				'permissions' => 'plugins.index',
				'priority' => 400,
				'icon' => 'list'
			),
			array(
				'name' => 'Repository',
				'url' => Route::get('backend')->uri(array('controller' => 'plugins', 'action' => 'repo')),
				'permissions' => 'plugins.repo',
				'priority' => 420,
				'icon' => 'cloud',
			),
		)
	)
);
