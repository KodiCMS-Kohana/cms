<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Content' => array(
		array(
			'name' => __('Scheduler'), 
			'url' => URL::backend('scheduler'),
			'priority' => 900,
			'icon' => 'calendar'
		)
	)
);
