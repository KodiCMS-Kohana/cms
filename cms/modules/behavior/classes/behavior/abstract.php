<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

abstract class Behavior_Abstract {

	/**
	 *
	 * @var Model_Page_Front 
	 */
	protected $_page = NULL;
	
	/**
	 *
	 * @var Behavior_Route
	 */
	protected $_router = NULL;

	/**
	 *
	 * @var array 
	 */
	protected $_settings = NULL;

	public function __construct() 
	{
		$this->_router = new Behavior_Route($this->routes());
	}

	/**
	 * 
	 * @return array
	 */
	public function routes()
	{
		return array();
	}
	
	/**
	 * 
	 * @return Behavior_Route
	 */
	public function router()
	{
		return $this->_router;
	}

	/**
	 * 
	 * @param string $uri
	 */
	public function execute_uri($uri) 
	{
		$method = $this->_router->find($uri);
		$this->{$method}();
		
		return $this;
	}

	/**
	 * 
	 * @param Model_Page_Front $page
	 * @return \Behavior_Abstract
	 */
	public function set_page( Model_Page_Front &$page )
	{
		$this->_page = &$page;
		return $this;
	}
	
	/**
	 * 
	 * @return Model_Page_Front
	 */
	public function page()
	{
		return $this->_page;
	}

	/**
	 * 
	 * @return \Behavior_Abstract
	 * @throws Kohana_Exception
	 */
	protected function _load_settings()
	{
		if( $this->page() === NULL )
			throw new Kohana_Exception('Page must be loaded');
		
		if( $this->_settings === NULL )
		{
			$this->_settings = ORM::factory('Page_Behavior_Setting')
				->find_by_page($this->page())
				->get('data', array());
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return string
	 */
	public function setting($key, $default = NULL)
	{
		$this->_load_settings();
		
		return Arr::get($this->_settings, $key, $default);
	}
	
	/**
	 * 
	 * @param Model_Page $page
	 * @return View
	 */
	public function get_page_settings(Model_Page $page)
	{
		$this->_page = $page;
		$this->_load_settings();

		return View::factory('behavior/' . $this->page()->behavior_id)
			->set('settings', $this->_settings)
			->set('behavior', $this)
			->set('page', $this->page());
	}

	abstract public function execute();
}