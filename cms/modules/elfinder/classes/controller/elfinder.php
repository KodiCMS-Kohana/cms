<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Elfinder
 * @category	Controller
 * @author		ButscHSter
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