<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Elfinder
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Elfinder extends Controller_System_Template {
	
	public $template = 'system/frontend';
	
	public function before()
	{
		parent::before();
		Assets::package(array('jquery', 'elfinder', 'jquery-ui', 'backbone', 'core', 'underscore'));
	}
	
	public function action_index()
	{
		$this->template->content = View::factory('elfinder/manager');
	}
}