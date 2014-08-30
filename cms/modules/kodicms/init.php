<?php defined('SYSPATH') or die('No direct access allowed.');

Assets_Package::add('core')
	->css('global', ADMIN_RESOURCES . 'css/common.css')
	->js(NULL, ADMIN_RESOURCES . 'js/core.min.js', 'backbone')
	->js('global', ADMIN_RESOURCES . 'js/backend.min.js', 'core');

Assets_Package::add('jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/jquery.min.js');

Assets_Package::add('bootstrap')
	->js(NULL, ADMIN_RESOURCES . 'libs/bootstrap-3.2.0/dist/js/bootstrap.min.js', 'jquery');

Assets_Package::add('underscore')
	->js(NULL, ADMIN_RESOURCES . 'libs/underscore-min.js', 'jquery');

Assets_Package::add('backbone')
	->js(NULL, ADMIN_RESOURCES . 'libs/backbone-min.js', 'underscore');

Assets_Package::add('jquery-ui')
	->js(NULL, ADMIN_RESOURCES . 'libs/jquery-ui/js/jquery-ui.min.js', 'jquery')
	->css(NULL, ADMIN_RESOURCES . 'libs/jquery-ui/css/jquery-ui.min.css', 'jquery');

Assets_Package::add('notify')
	->js(NULL, ADMIN_RESOURCES . 'libs/pnotify/jquery.pnotify.min.js', 'jquery')
	->css(NULL, ADMIN_RESOURCES . 'libs/pnotify/jquery.pnotify.default.css', 'jquery');

Assets_Package::add('dropzone')
	->css(NULL, ADMIN_RESOURCES . 'libs/dropzone/css/basic.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/dropzone/dropzone.min.js', 'jquery');

Assets_Package::add('fancybox')
	->css(NULL, ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.pack.js', 'jquery');

Assets_Package::add('datepicker')
	->css(NULL, ADMIN_RESOURCES . 'libs/datepicker/jquery.datetimepicker.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/datepicker/jquery.datetimepicker.js', 'jquery');

Assets_Package::add('prism')
	->css(NULL, ADMIN_RESOURCES . 'libs/prismjs/prism.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/prismjs/prism.js', 'jquery');


// Подключение пакета после загрузки модулей, чтобы определить текущий язык
Observer::observe('modules::after_load', function() {
	Assets_Package::add('select2')
		->js(NULL, ADMIN_RESOURCES . 'libs/select2/select2.min.js', 'jquery')
		->js(NULL . I18n::lang_short(), ADMIN_RESOURCES . 'libs/select2/select2_locale_'.I18n::lang_short().'.js', 'select2');
});
