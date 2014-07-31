<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'System',
		'children' => array(
			array(
				'name' => 'Users', 
				'url' => Route::get('backend')->uri(array('controller' => 'users')),
				'permissions' => 'users.index',
				'priority' => 200,
				'icon' => 'user',
				'divider' => TRUE,
			),
			array(
				'name' => 'Roles', 
				'url' => Route::get('backend')->uri(array('controller' => 'roles')),
				'permissions' => 'roles.index',
				'priority' => 300,
				'icon' => 'group'
			)
		)
	)
);
