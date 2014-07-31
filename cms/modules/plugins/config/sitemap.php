<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'System',
		'children' => array(
			array(
				'name' => 'Plugins',
				'divider' => TRUE,
				'icon' => 'puzzle-piece',
				'children' => array(
					array(
						'name' => 'List',
						'url' => Route::get('backend')->uri(array('controller' => 'plugins')),
						'permissions' => 'plugins.index',
						'priority' => 400,
					),
					array(
						'name' => 'Repository',
						'url' => Route::get('backend')->uri(array('controller' => 'plugins', 'action' => 'repo')),
						'permissions' => 'plugins.repo',
						'priority' => 420,
					),
				)
			)
		)
	)
);
