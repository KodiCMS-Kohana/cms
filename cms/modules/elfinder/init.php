<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('modules::after_load', function() {
	Assets_Package::add('elfinder')
		->js('elfinder.lib', ADMIN_RESOURCES . 'libs/elfinder/js/elfinder.min.js', 'global')
		->js('elfinder.'.I18n::lang_short(), ADMIN_RESOURCES . 'libs/elfinder/js/i18n/elfinder.'.I18n::lang_short().'.js', 'elfinder.lib')
		->css('elfinder.lib', ADMIN_RESOURCES . 'libs/elfinder/css/elfinder.min.css')
		->css('elfinder', ADMIN_RESOURCES . 'css/elfinder.css', 'elfinder.lib');
});
