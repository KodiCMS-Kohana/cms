<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('modules::after_load', function($plugin) {
	Assets_Package::add('redactor')
		->js('redactor.' . I18n::lang(), ADMIN_RESOURCES . 'vendors/redactor/'.I18n::lang().'.js', 'jquery')
		->js('redactor.min', ADMIN_RESOURCES . 'vendors/redactor/redactor.min.js', 'jquery')
		->js('redactor', ADMIN_RESOURCES . 'js/redactor.js', 'global')
		->css('redator', ADMIN_RESOURCES . 'vendors/redactor/redactor.css');
}, $plugin);