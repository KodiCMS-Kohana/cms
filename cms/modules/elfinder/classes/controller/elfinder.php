<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Elfinder extends Controller_System_Template {
	
	public $template = 'layouts/frontend';
	
	public function before()
	{
		parent::before();

		if($this->auto_render === TRUE)
		{
			$this->styles = array(
				ADMIN_RESOURCES . 'libs/jquery-ui/css/flick/jquery-ui-1.10.2.custom.css',
				ADMIN_RESOURCES . 'libs/elfinder/css/elfinder.min.css',
			);
			
			$this->scripts = array(
				ADMIN_RESOURCES . 'libs/jquery-1.8.1.min.js',
				ADMIN_RESOURCES . 'libs/jquery-ui/js/jquery-ui-1.10.2.custom.min.js',
				ADMIN_RESOURCES . 'libs/underscore-min.js',
				ADMIN_RESOURCES . 'libs/backbone-min.js',
				ADMIN_RESOURCES . 'js/backend.js',
				ADMIN_RESOURCES . 'libs/elfinder/js/elfinder.min.js',
				ADMIN_RESOURCES . 'libs/elfinder/js/i18n/elfinder.ru.js',
			);
		}
	}
	
	public function action_index()
	{
		$this->template->content = View::factory('elfinder/manager');
	}
}