<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Model_Widget_Decorator {
	
	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var string 
	 */
	public $type;
	
	/**
	 *
	 * @var string 
	 */
	public $name;

	/**
	 *
	 * @var string 
	 */
	public $description = '';
	
	/**
	 *
	 * @var string 
	 */
	public $header = NULL;

	/**
	 *
	 * @var strimg
	 */
	public $template = NULL;
	
	/**
	 *
	 * @var array
	 */
	public $template_params = array();

	/**
	 *
	 * @var string
	 */
	public $block = NULL;
	
	/**
	 *
	 * @var bool 
	 */
	public $caching = FALSE;
	
	/**
	 *
	 * @var integer 
	 */
	public $cache_lifetime = Date::HOUR;
	
	/**
	 *
	 * @var bool 
	 */
	public $throw_404 = FALSE;
	
	/**
	 *
	 * @var array 
	 */
	protected $_data = array();

	/**
	 * 
	 * @param array $params
	 */
	public function render($params = array())
	{
		if(Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Widget render', __CLASS__);
		}

		if( empty($this->template) ) 
		{
			if( ($this->template = Kohana::find_file('views', 'widgets/template/' . $this->type)) === FALSE  )
			{
				$this->template = Kohana::find_file('views', 'widgets/template/default');
			}
		}
		else
		{
			$this->template = SNIPPETS_SYSPATH . $this->template . EXT;
		}
		
		$allow_omments = (bool) Arr::get($params, 'comments');

		echo "<!--{Widget: {$this->name}}-->";
		
		if( 
			$this->caching === TRUE 
		AND 
			! Fragment::load($this->get_cache_id(), $this->cache_lifetime)
		)
		{
			echo $this->_fetch_render($params);
			Fragment::save();
		}
		else if( ! $this->caching )
		{
			echo $this->_fetch_render($params);
		}

		echo "<!--{/Widget: {$this->name}}-->";
		
		if(isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
	}
	
	/**
	 * 
	 * @param array $params
	 * @return View
	 */
	protected function _fetch_render($params)
	{
		$params = Arr::merge($params, $this->template_params);
		$data = $this->fetch_data();
		$data['params'] = $params;
		$data['header'] = $this->header;
		$data['page'] = Context::instance()->get_page();
		return View_Front::factory($this->template, $data);
	}
	
	/**
	 * @return array
	 */
	abstract public function fetch_data();
	
	/**
	 * @param array $data
	 */
	public function set_values(array $data)
	{
		foreach($data as $key => $value)
		{
			$this->{$key} = $value;
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @return \Model_Widget_Decorator
	 */
	public function set( $name, $value )
	{
		$this->_data[$name] = $value;
		return $this;
	}
	
	public function get( $name, $default = NULL)
	{
		return Arr::get($this->_data, $name, $default);
	}

	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value )
	{
		$this->set($name, $value);
	}
	
	/**
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->get( $name );
	}

	/**
	 * 
	 * @param array $data
	 * @return \Model_Widget_Decorator
	 */
	public function set_cache_settings(array $data)
	{
		$this->caching = (bool) Arr::get($data, 'caching', FALSE);
		$this->cache_lifetime = (int) Arr::get($data, 'cache_lifetime');
		
		return $this;
	}

	/**
	 * 
	 * @return string
	 */
	public function get_cache_id()
	{
		return 'Widget::' . $this->type . '::' . $this->id;
	}
	
	/**
	 * 
	 * @return \Model_Widget_Decorator
	 */
	public function clear_cache()
	{
		if($this->caching === TRUE)
		{
			Fragment::delete($this->get_cache_id());
		}

		return $this;
	}

	/**
	 * 
	 * @param array $params
	 */
	public function run($params = array()) 
	{
		return $this->render($params);
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function loaded()
	{
		return isset($this->id) AND $this->id > 0;
	}
	
	public function load_template_data()
	{
		return array();
	}

	/**
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->render();
	}
}