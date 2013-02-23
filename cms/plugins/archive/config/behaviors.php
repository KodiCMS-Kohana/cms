<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'archive' => array(
		'link' => 'archive/:id',
		'limit' => 5
	),
	
	'archive_day_index' => array(
		'name' => 'archive',
		'link' => 'archive/:id',
		'limit' => 5
	),
	
	'archive_month_index' => array(
		'name' => 'archive',
		'link' => 'archive/:id',
		'limit' => 3
	),
	
	'archive_year_index' => array(
		'name' => 'archive',
		'link' => 'archive/:id',
		'limit' => 3
	),
);