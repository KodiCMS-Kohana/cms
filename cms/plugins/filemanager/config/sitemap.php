<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Content' => array(
		array(
			'name' => __('File manager'), 
			'url' => URL::backend('filemanager'),
			'priority' => 999,
			'permissions' => array('administrator', 'developer'),
		)
	)
);
