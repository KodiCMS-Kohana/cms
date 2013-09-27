<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'System' => array(
		array(
			'name' => __('Settings'),
			'url' => Route::url('backend', array('controller' => 'setting')),
			'permissions' => 'setting.index',
			'priority' => 100,
			'icon' => 'cog',
		)
	),
	
	'Design' => array(
		array(
			'name' => __('Layouts'), 
			'url' => Route::url('backend', array('controller' => 'layout')),
			'permissions' => 'layout.index',
			'priority' => 100,
			'icon' => 'desktop'
		)
	),
	
	'Content' => array(
		array(
			'name' => __('Pages'),
			'url' => URL::backend('page'),
			'permissions' => 'page.index',
			'priority' => 100,
			'icon' => 'sitemap'
		)
	)

);
