<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'chosen',
	'title' => 'jQuery chosen',
	'description' => 'Изменяет внешний вид всех select элементов',
	'javascripts' => array(
		'vendors/chosen/chosen.jquery.min.js',
		'chosen.js',
	),
	'css' => 'vendors/chosen/chosen.css',
	'iframe' => FALSE
) )->register();