<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Dashboard
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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
	protected $_update_settings_page = FALSE;


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
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_update_settings_page()
	{
		return (bool) $this->_update_settings_page;
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
		return $this->backend_template = substr($this->type(), strlen('dashboard_'));
	}
	
	public function frontend_template()
	{
		return $this->frontend_template = substr($this->type(), strlen('dashboard_'));
	}
	
	protected function _serialize_vars()
	{
		$vars = parent::_serialize_vars();
		
		unset(
			$vars['_update_settings_page'],
			$vars['_multiple'],
			$vars['crumbs'],
			$vars['roles'],
			$vars['media'],
			$vars['throw_404'],
			$vars['caching'],
			$vars['cache_lifetime'],
			$vars['cache_tags']
		);
		
		return $vars;
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