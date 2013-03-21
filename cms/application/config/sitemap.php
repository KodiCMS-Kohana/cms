<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'System' => array(
		array(
			'name' => __('Settings'), 
			'url' => URL::backend('setting'),
			'priority' => 100,
			'icon' => 'cog',
		),
		array(
			'name' => __('Users'), 
			'url' => URL::backend('user'),
			'priority' => 200,
			'icon' => 'user'
		)
	),
	
	'Design' => array(
		array(
			'name' => __('Layouts'), 
			'url' => URL::backend('layout'),
			'permissions' => array('administrator','developer'),
			'priority' => 100,
			'icon' => 'desktop'
		),
	),
	
	'Content' => array(
		array(
			'name' => __('Pages'), 
			'url' => URL::backend('page'),
			'permissions' => array('administrator','developer','editor'),
			'priority' => 100,
			'icon' => 'sitemap'
		)
	)

);
