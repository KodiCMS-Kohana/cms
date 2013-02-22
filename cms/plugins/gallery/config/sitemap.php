<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'Content' => array(
		array(
			'name' => __('Gallery'), 
			'url' => URL::backend('photos'),
			'permissions' => array('login'),
			'icon' => UI::icon('envelope'),
			'priority' => 200
		)
	)

);