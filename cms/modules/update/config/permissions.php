<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'update' => array(
		array(
			'action' => 'index',
			'description' => 'View updates'
		),
		array(
			'action' => 'database',
			'description' => 'View database changes'
		),
		array(
			'action' => 'database_apply',
			'description' => 'Apply database changes'
		),
		array(
			'action' => 'patches',
			'description' => 'View patches page'
		)
	),
);