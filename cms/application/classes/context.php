<?php defined('SYSPATH') or die('No direct access allowed.');

class Context {
	
	protected static $_instance = NULL;

	/**
	 * 
	 * @param array $params
	 * @return Context
	 */
	public static function instance(array $params = array())
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
	
	public function __construct(array $params = array())
	{
		
	}

		/**
	 * 
	 * @param Model_Page_Front $page
	 * @return \Context
	 */
	public function set_page( Model_Page_Front $page )
	{
		$this->_page = $page;
		
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
	public function get_widget($id)
	{
		return Arr::get($this->_widgets, $id);
	}
	
	/**
	 * 
	 * @param string $block
	 * @return Model_Widget_Decorator
	 */
	public function get_widget_by_block( $block )
	{
		if( !empty($block) AND isset($this->_blocks[$block]))
		{
			return $this->_blocks[$block];
		}
		
		return NULL;
	}
	
	/**
	 * 
	 * @param array $widgets
	 * @return \Context
	 */
	public function register_widgets( array & $widgets )
	{
		$this->_widgets = & $widgets;

		foreach($this->_widgets as & $widget ) 
		{
			if( $widget->block ) 
			{
				$this->_blocks[$widget->block] = $widget;
			}
		}
		
		return $this;
	}
}