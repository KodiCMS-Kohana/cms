<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Elfinder
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_FileManager extends Controller_System_Backend {
	
	public function action_index()
	{
		$this->set_title(__('File manager'));

		Assets::package(array('elfinder', 'jquery-ui', 'ace'));

		$this->template->content = View::factory('elfinder/filemanager');
	}
}