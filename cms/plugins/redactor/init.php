<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Plugin::factory('redactor', array(
	'title' => 'Redactor',
	'description' => 'Create word-processed text on the web using a reliable, fast and unbelievably beautiful editor.',
	'author' => 'ButscH'
))->register();

Observer::observe('modules::after_load', function() {
	Assets_Package::add('redactor')
		->js('redactor.' . I18n::lang(), ADMIN_RESOURCES . 'vendors/redactor/'.I18n::lang().'.js', 'jquery')
		->js('redactor.min', ADMIN_RESOURCES . 'vendors/redactor/redactor.min.js', 'jquery')
		->js('redactor', ADMIN_RESOURCES . 'js/redactor.js', 'jquery')
		->css('redator', ADMIN_RESOURCES . 'vendors/redactor/redactor.css');
});
