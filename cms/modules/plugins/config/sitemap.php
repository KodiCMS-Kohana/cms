<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'System' => array(
		array(
			'name' => __('Plugins'), 
			'url' => URL::backend('plugins'),
			'priority' => 999,
			'divider' => TRUE
		)
	)

);
