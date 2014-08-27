<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Decorator
 * @author		ButscHSter
 */
abstract class Model_Widget_Decorator_Dashboard extends Model_Widget_Decorator {
	
	/**
	 *
	 * @var string 
	 */
	public $frontend_template_preffix = 'dashboard';
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_multiple = FALSE;
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_multiple()
	{
		return $this->_multiple;
	}
	
	public function render(array $params = array())
	{
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Widget render', $this->name);
		}

		$this->_fetch_template();
		$this->set_params($params);

		return $this->_fetch_render();

		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
	}
	
	public function fetch_backend_content()
	{
		try
		{
			$content = View::factory('widgets/dashboard/backend/' . $this->backend_template(), array(
					'widget' => $this
				))->set($this->backend_data());
		}
		catch (Kohana_Exception $e)
		{
			$content = NULL;
		}
		
		return $content;
	}
	
	public function backend_template()
	{
		return $this->backend_template = substr($this->type, strlen('dashboard_'));
	}
	
	public function frontend_template()
	{
		return $this->frontend_template = substr($this->type, strlen('dashboard_'));
	}

	public function __sleep()
	{
		$vars = get_object_vars($this);

		unset(
			$vars['_ctx'],
			$vars['type'],
			$vars['template'],
			$vars['name'], 
			$vars['description'],
			$vars['backend_template'],
			$vars['frontend_template'],
			$vars['use_template'],
			$vars['block'],
			$vars['position'],
			$vars['template_params']
		);

		return array_keys($vars);
	}

	protected function _fetch_render()
	{
		$data = $this->fetch_data();
		$data['params'] = $this->template_params;
		
		return View_Front::factory($this->template, $data)
			->bind('header', $this->header)
			->bind('widget', $this);
	}
}