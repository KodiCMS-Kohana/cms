<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Elfinder
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_FileManager extends Controller_System_Backend {
	
	public function action_index()
	{
		$this->set_title(__('File manager'));

		Assets::package(array('elfinder', 'jquery-ui', 'ace'));

		$this->template->content = View::factory('elfinder/filemanager');
	}
}