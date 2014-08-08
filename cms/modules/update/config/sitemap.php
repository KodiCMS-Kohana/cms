<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'System',
		'children' => array(
			array(
				'name' => 'Update',
				'icon' => 'cloud-download',
				'children' => array(
					array(
						'name' => 'Information',
						'url' => Route::get('backend')->uri(array('controller' => 'update')),
						'permissions' => 'update.index',
						'priority' => 400,
						'icon' => 'exclamation-circle'
					),
					array(
						'name' => 'Database',
						'url' => Route::get('backend')->uri(array('controller' => 'update', 'action' => 'database')),
						'permissions' => 'update.database',
						'priority' => 410,
						'icon' => 'database',
					),
					array(
						'name' => 'Patches',
						'url' => Route::get('backend')->uri(array('controller' => 'update', 'action' => 'patches')),
						'permissions' => 'update.patches',
						'priority' => 410,
						'icon' => 'file-code-o'
					)
				)
			)
		)
	)
);
