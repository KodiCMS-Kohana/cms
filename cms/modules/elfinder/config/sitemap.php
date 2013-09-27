<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Content' => array(
		array(
			'name' => __('File manager'), 
			'url' => Route::url('backend', array('controller' => 'filemanager')),
			'priority' => 999,
			'permissions' => 'filemanager.index',
			'icon' => 'folder-open'
		)
	)
);
