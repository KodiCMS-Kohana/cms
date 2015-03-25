<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Content',
		'icon' => 'pencil-square-o',
		'priority' => 200,
	),
	array(
		'name' => 'System',
		'icon' => 'cog',
		'priority' => 8000,
		'children' => array(
			array(
				'name' => 'Information',
				'url' => Route::get('backend')->uri(array('controller' => 'system', 'action' => 'information')),
				'permissions' => 'system.information',
				'priority' => 90,
				'icon' => 'info-circle',
			),
			array(
				'name' => 'Settings',
				'url' => Route::get('backend')->uri(array('controller' => 'system', 'action' => 'settings')),
				'permissions' => 'system.settings',
				'priority' => 100,
				'icon' => 'cog',
			)
		)
		
	),
	array(
		'name' => 'Design',
		'icon' => 'desktop',
		'priority' => 7000,
		'children' => array(
			array(
				'name' => 'Layouts', 
				'url' => Route::get('backend')->uri(array('controller' => 'layout')),
				'permissions' => 'layout.index',
				'priority' => 100,
				'icon' => 'desktop'
			)
		)
	),
);
