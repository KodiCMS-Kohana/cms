<?php defined('SYSPATH') or die('No direct access allowed.');

Assets_Package::add('core')
	->css('global', ADMIN_RESOURCES . 'css/common.css')
	->js(NULL, ADMIN_RESOURCES . 'js/core.min.js', 'backbone')
	->js('global', ADMIN_RESOURCES . 'js/backend.min.js', 'core');

Assets_Package::add('jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/jquery.min.js');

Assets_Package::add('bootstrap')
	->js(NULL, ADMIN_RESOURCES . 'libs/bootstrap-3.3.1/dist/js/bootstrap.min.js', 'jquery');

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
	->css(NULL, ADMIN_RESOURCES . 'libs/dropzone/min/basic.min.css', 'jquery')
	->css(NULL, ADMIN_RESOURCES . 'libs/dropzone/min/dropzone.min.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/dropzone/min/dropzone.min.js', 'jquery');

Assets_Package::add('fancybox')
	->css(NULL, ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.pack.js', 'jquery');

Assets_Package::add('datepicker')
	->css(NULL, ADMIN_RESOURCES . 'libs/datepicker/jquery.datetimepicker.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/datepicker/jquery.datetimepicker.min.js', 'jquery');

Assets_Package::add('prism')
	->css(NULL, ADMIN_RESOURCES . 'libs/prismjs/prism.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/prismjs/prism.js', 'jquery');

Assets_Package::add('colorpicker')
	->css(NULL, ADMIN_RESOURCES . 'libs/colorpicker/css/colorpicker.css', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'libs/colorpicker/js/colorpicker.js', 'jquery');

Assets_Package::add('editable')
	->js(NULL, ADMIN_RESOURCES . 'libs/bootstrap-editable-1.5.1/js/bootstrap-editable.min.js', 'bootstrap');

Assets_Package::add('nestable')
	->js(NULL, ADMIN_RESOURCES . 'libs/nestable/jquery.nestable.min.js', 'bootstrap');

Assets_Package::add('ace')
	->js('ace-library', ADMIN_RESOURCES . 'libs/ace/src-min/ace.js', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'js/ace.js', 'jquery');

Assets_Package::add('steps')
	->js(NULL, ADMIN_RESOURCES . 'libs/steps/jquery.steps.min.js', 'jquery');
	
Assets_Package::add('chart')
	->js(NULL, ADMIN_RESOURCES . 'libs/highcharts/highcharts.js', 'jquery');

Assets_Package::add('ckeditor')
	->js('ckeditor-library', ADMIN_RESOURCES . 'libs/ckeditor/ckeditor.js', 'jquery')
	->js(NULL, ADMIN_RESOURCES . 'js/ckeditor.js', 'jquery');

WYSIWYG::add('ace', 'Ace', NULL, NULL, WYSIWYG::TYPE_CODE);
WYSIWYG::add('ckeditor', 'CKEditor');

// Подключение пакета после загрузки модулей, чтобы определить текущий язык
Observer::observe('modules::after_load', function() {
	Assets_Package::add('select2')
		->js(NULL, ADMIN_RESOURCES . 'libs/select2/select2.min.js', 'jquery')
		->js(NULL . I18n::lang_short(), ADMIN_RESOURCES . 'libs/select2/select2_locale_'.I18n::lang_short().'.js', 'select2');
	
	Assets_Package::add('validate')
		->js(NULL, ADMIN_RESOURCES . 'libs/validation/jquery.validate.min.js', 'jquery')
		->js(NULL . I18n::lang_short(), ADMIN_RESOURCES . 'libs/validation/localization/messages_' . I18n::lang_short() . '.min.js', 'validate');
});

Observer::observe('view_setting_plugins', function() {
	echo View::factory('ace/settings');
});