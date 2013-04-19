<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_FileManager extends Controller_System_Backend {
	
	public function action_index()
	{		
		Assets::css('elfinder', ADMIN_RESOURCES . 'libs/elfinder/css/elfinder.min.css');
		Assets::js('elfinder', ADMIN_RESOURCES . 'libs/elfinder/js/elfinder.min.js', 'jquery');
		Assets::js('elfinder.ru', ADMIN_RESOURCES . 'libs/elfinder/js/i18n/elfinder.ru.js', 'jquery');

		$this->template->content = View::factory('elfinder/filemanager');
	}
}