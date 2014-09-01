<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		ButscHSter
 */
class Controller_System_Handler extends Controller_System_Controller
{
	public function action_index()
	{
		$id = (int) $this->request->param('id');

		if(Security::check($this->request->post('csrf')))
		{
			throw new Exception('Security token not check');
		}
	
		Observer::notify('handler_requested', $id);
		
		$widget = Widget_Manager::load($id);
		if($widget === NULL OR ! $widget->is_handler())
		{
			$this->go_home();
		}
		
		$widget->run();
	}
}