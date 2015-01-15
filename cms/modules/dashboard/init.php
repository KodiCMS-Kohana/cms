<?php defined('SYSPATH') or die('No direct access allowed.');

Assets_Package::add('weather')
	->js(NULL, ADMIN_RESOURCES . 'libs/weather.js');

Assets_Package::add('gridster')
	->js(NULL, ADMIN_RESOURCES . 'libs/gridster/jquery.gridster.js', 'jquery')
	->css(NULL, ADMIN_RESOURCES . 'libs/gridster/jquery.gridster.css');
