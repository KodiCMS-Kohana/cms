<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'archive' => array(
		'file' => 'archive/archive.php',
		'link' => 'archive/:id',
		'limit' => 5
	),
	
	'archive_day_index' => array(
		'file' => 'archive/archive.php',
		'link' => 'archive/:id',
		'limit' => 5
	),
	
	'archive_month_index' => array(
		'file' => 'archive/archive.php',
		'link' => 'archive/:id',
		'limit' => 3
	),
	
	'archive_year_index' => array(
		'file' => 'archive/archive.php',
		'link' => 'archive/:id',
		'limit' => 3
	),
);