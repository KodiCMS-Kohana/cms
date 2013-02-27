<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'System' => array(
		array(
			'name' => __('Backup'), 
			'url' => URL::backend('backup'),
			'priority' => 110
		)
	)
);
