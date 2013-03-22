<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Design' => array(
		array(
			'divider' => TRUE,
			'name' => __('Widgets'), 
			'url' => URL::backend('widgets'),
			'permissions' => array('administrator','developer'),
			'priority' => 300,
			'icon' => 'th-large'
		),
	)
);
