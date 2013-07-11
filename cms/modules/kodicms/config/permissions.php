<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'page' => array(
		array(
			'action' => 'index',
			'description' => 'View pages'
		),
		array(
			'action' => 'add',
			'description' => 'Add pages'
		),
		array(
			'action' => 'edit',
			'description' => 'Edit pages'
		),
		array(
			'action' => 'sort',
			'description' => 'Sort pages'
		),
		array(
			'action' => 'permissions',
			'description' => 'Set page permissions'
		),
		array(
			'action' => 'delete',
			'description' => 'Delete pages'
		),
	),
	'layout' => array(
		array(
			'action' => 'index',
			'description' => 'View layouts'
		),
		array(
			'action' => 'add',
			'description' => 'Add layout'
		),
		array(
			'action' => 'edit',
			'description' => 'Edit layout'
		),
		array(
			'action' => 'delete',
			'description' => 'Delete layout'
		),
	),
);