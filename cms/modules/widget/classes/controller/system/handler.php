<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_System_Handler extends Controller_System_Controller
{
	public function before()
	{
		parent::before();

		$this->_ctx = Context::instance();

		$this->_ctx
			->request($this->request)
			->response($this->response);

		View_Front::bind_global('ctx', $this->_ctx);
	}
	
	public function action_index()
	{
		$id = (int) $this->request->param('id');

		Observer::notify('handler_requested', $id);

		$widget = Widget_Manager::load($id);
		if ($widget === NULL OR ! $widget->is_handler())
		{
			$this->go_home();
		}

		$widget->run();
	}
}