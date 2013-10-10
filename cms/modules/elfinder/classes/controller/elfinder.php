<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Elfinder extends Controller_System_Template {
	
	public $template = 'layouts/frontend';
	
	public function before()
	{
		parent::before();
		
		Assets::js('jquery', ADMIN_RESOURCES . 'libs/jquery-2.0.3.min.js');
		
		Assets::css('jquery-ui', ADMIN_RESOURCES . 'libs/jquery-ui/css/flick/jquery-ui-1.10.2.custom.css', 'jquery');
		Assets::js('jquery-ui', ADMIN_RESOURCES . 'libs/jquery-ui/js/jquery-ui-1.10.2.custom.min.js', 'jquery');
		
		Assets::js('underscore', ADMIN_RESOURCES . 'libs/underscore-min.js', 'jquery');
		Assets::js('backbone', ADMIN_RESOURCES . 'libs/backbone-min.js', 'underscore');
		
		Assets::css('elfinder.lib', ADMIN_RESOURCES . 'libs/elfinder/css/elfinder.min.css');
		Assets::js('elfinder.lib', ADMIN_RESOURCES . 'libs/elfinder/js/elfinder.min.js', 'global');
		Assets::js('elfinder.ru', ADMIN_RESOURCES . 'libs/elfinder/js/i18n/elfinder.ru.js', 'elfinder');

		Assets::js('global', ADMIN_RESOURCES . 'js/backend.js', 'backbone');
	}
	
	public function action_index()
	{
		$this->template->content = View::factory('elfinder/manager');
	}
}