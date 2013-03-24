<?php defined('SYSPATH') or die('No direct access allowed.');

class Context {
	
	protected static $_instance = NULL;

	/**
	 * 
	 * @param array $params
	 * @return Context
	 */
	public static function & instance(array $params = array())
	{
		if(self::$_instance === NULL)
		{
			self::$_instance = new Context($params);
			return self::$_instance;
		}

		return self::$_instance;
	}
	
	/**
	 *
	 * @var Model_Page_Front 
	 */
	protected $_page = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected $_widgets = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_blocks = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_params = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_injections = array();

	public function __construct(array $params = array())
	{
		$this->_params = $params;
	}
	
	public function &get($param) 
	{
		$result = NULL;
		
		if(isset($this->_params[$param]))
		{
			$result = & $this->_params[$param];
		}
		
		return $result;
	}
	
	/**
	 * 
	 * @param string $param
	 * @param mixed $value
	 * @return \Context
	 */
	public function set($param, $value)
	{
		$this->_params[$param] = & $value;

		if( ! empty($this->_injections[$param]) 
				AND is_array($this->_injections[$param])) 
		{
			foreach($this->_injections[$param] as $id => $fields)
			{
				if(isset($this->_widgets[$id]))
				{
					foreach($fields as $field => $param_name)
					{
						$this->inject($this->_widgets[$id], 
								$field, $this->get($param_name));
					}
				}
			}

			unset($this->_injections[$param]);
		}
		
		return $this;
	}

	/**
	 * 
	 * @param Model_Page_Front $page
	 * @return \Context
	 */
	public function set_page( Model_Page_Front & $page )
	{
		$this->_page = & $page;
		
		return $this;
	}
	
	/**
	 * 
	 * @return Model_Page_Front
	 */
	public function get_page()
	{
		return $this->_page;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return Model_Widget_Decorator
	 */
	public function & get_widget($id)
	{
		$result = NULL;
		
		if(isset($this->_widgets[$id]))
		{
			$result = & $this->_widgets[$id];
		}

		return $result;
	}
	
	/**
	 * 
	 * @param string $block
	 * @return Model_Widget_Decorator
	 */
	public function & get_widget_by_block( $block )
	{
		$result = NULL;
		if( !empty($block) AND isset($this->_blocks[$block]))
		{
			$result = & $this->_blocks[$block];
		}
		
		return $result;
	}
	
	/**
	 * 
	 * @param array $widgets
	 * @return \Context
	 */
	public function register_widgets( array & $widgets )
	{
		foreach($widgets as $id => & $widget ) 
		{
			$this->_widgets[$id] = $widget;
			if( $widget->block ) 
			{
				$this->_blocks[$widget->block] = $widget;
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param Model_Widget_Decorator | View $widget
	 * @param string $field
	 * @param mixed $value
	 * @return \Context
	 */
	public function inject( & $widget, $field, $value) 
	{
		if($widget instanceof Model_Widget_Decorator 
				OR $widget instanceof View)
		{
			if( ! empty($field) )
			{
				if(method_exists($widget, "set_{$field}"))
				{
					call_user_func(array( & $widget, "set_{$field}"), $value);
				}
				else
				{
					$widget->{$field} = $value;
				}				
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param Model_Widget_Decorator|View $widget
	 * @param string $field
	 * @param mixed $param_key
	 * @return \Context
	 */
	public function inject_param( & $widget, $field, $param_key) 
	{
		if($widget instanceof Model_Widget_Decorator 
				OR $widget instanceof View)
		{
			if( strpos( $param_key, '/' ) !== FALSE)
			{
				list($name, $index) = explode('/', $param_key, 2);
			}

			$value = $this->get($param_key);
			if($value !== NULL)
			{
				$this->inject($widget, $field, $value);
			}
			else if(isset($name))
			{
				$this->_injections[$name][$widget->id][$field] = $param_key;
			}
		}
		
		return $this;
	}
}