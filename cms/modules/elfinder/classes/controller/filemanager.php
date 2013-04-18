<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_FileManager extends Controller_System_Backend {
	
	public function action_index()
	{	
		$this->template->styles = array(
			ADMIN_RESOURCES . 'libs/elfinder/css/elfinder.min.css',
		);

		$this->template->scripts = array(
			ADMIN_RESOURCES . 'libs/elfinder/js/elfinder.min.js',
			ADMIN_RESOURCES . 'libs/elfinder/js/i18n/elfinder.ru.js',
		);
		$this->template->content = View::factory('elfinder/filemanager');
	}
}