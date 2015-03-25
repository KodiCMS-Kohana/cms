<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'calendar' => array(
		array(
			'action' => 'index',
			'description' => 'View calendar'
		)
	),
	'jobs' => array(
		array(
			'action' => 'index',
			'description' => 'View jobs'
		),
		array(
			'action' => 'add',
			'description' => 'Add job'
		),
		array(
			'action' => 'edit',
			'description' => 'Edit job'
		),
		array(
			'action' => 'delete',
			'description' => 'Delete job'
		),
		array(
			'action' => 'run',
			'description' => 'Run job'
		)
	)
);