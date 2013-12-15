<?php defined('SYSPATH') or die('No direct access allowed.');

$widgets = array(
	array(
		'type' => 'archive_month',
		'data' => array (
			'name' => 'Archive by month',
			'template' => 'archive-by-month',
			'caching' => 1,
			'cache_lifetime' => 2629744,
			'header' => 'Archive by month',
			'page_id' => 8,
		),
		'blocks' => array (
			8 => 'extended'
		)
	),
	array(
		'type' => 'archive_hl',
		'data' => array (
			'name' => 'Archive headline',
			'template' => 'archive-headline',
			'cache_tags' => 'pages,page_parts,page_tags',
			'page_id' => 8,
		),
		'blocks' => array (
			6 => 'body'
		)
	),
);

foreach ($widgets as $key => $widget)
{
	Widget_Manager::install($widget);
}