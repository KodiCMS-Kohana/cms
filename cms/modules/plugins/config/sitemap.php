<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'System' => array(
		array(
			'name' => __('Plugins'), 
			'url' => Route::url('backend', array('controller' => 'plugins')),
			'priority' => 999,
			'divider' => TRUE,
			'icon' => 'puzzle-piece',
			'permissions' => 'plugins.index',
		)
	)

);
