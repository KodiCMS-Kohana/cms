<?php defined('SYSPATH') or die('No direct access allowed.');

Assets_Package::add('backbone')
	->js('underscore', ADMIN_RESOURCES . 'libs/underscore-min.js', 'jquery')
	->js('backbone', ADMIN_RESOURCES . 'libs/backbone-min.js', 'underscore');

Assets_Package::add('jquery-ui')
	->js('jquery-ui', ADMIN_RESOURCES . 'libs/jquery-ui/js/jquery-ui.custom.min.js', 'jquery')
	->css('jquery-ui', ADMIN_RESOURCES . 'libs/jquery-ui/css/flick/jquery-ui-1.10.2.custom.css', 'jquery');

Assets_Package::add('notify')
	->js('notify', ADMIN_RESOURCES . 'libs/pnotify/jquery.pnotify.min.js', 'jquery')
	->css('notify', ADMIN_RESOURCES . 'libs/pnotify/jquery.pnotify.default.css', 'jquery');

Observer::observe('modules::after_load', function() {
	Assets_Package::add('select2')
		->js('select2', ADMIN_RESOURCES . 'libs/select2/select2.min.js', 'jquery')
		->js('select2' . I18n::lang_short(), ADMIN_RESOURCES . 'libs/select2/select2_locale_'.I18n::lang_short().'.js', 'select2')
		->css('select2', ADMIN_RESOURCES . 'libs/select2/select2.css', 'jquery');
});
