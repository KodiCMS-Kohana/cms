<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'System' => array(
		array(
			'name' => __('Users'), 
			'url' => Route::url('backend', array('controller' => 'users')),
			'permissions' => 'users.index',
			'priority' => 200,
			'icon' => 'user',
			'divider' => TRUE,
		),
		array(
			'name' => __('Roles'), 
			'url' => Route::url('backend', array('controller' => 'roles')),
			'permissions' => 'roles.index',
			'priority' => 300,
			'icon' => 'group'
		)
	),
);
