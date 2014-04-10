<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'System',
		'children' => array(
			array(
				'name' => 'Users', 
				'url' => Route::url('backend', array('controller' => 'users')),
				'permissions' => 'users.index',
				'priority' => 200,
				'icon' => 'user',
				'divider' => TRUE,
			),
			array(
				'name' => 'Roles', 
				'url' => Route::url('backend', array('controller' => 'roles')),
				'permissions' => 'roles.index',
				'priority' => 300,
				'icon' => 'group'
			)
		)
	)
);
