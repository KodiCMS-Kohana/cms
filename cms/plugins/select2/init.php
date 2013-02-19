<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'select2',
	'title' => 'jQuery Select2',
	'description' => 'Select2 is a jQuery based replacement for select boxes. It supports searching, remote data sets, and infinite scrolling of results. Look and feel of Select2 is based on the excellent Chosen library.',
	'javascripts' => array(
		'vendors/select2/select2.min.js',
		'select2.js',
	),
	'css' => 'vendors/select2/select2.css',
) )->register();