<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

abstract class Behavior_Abstract {
	
	// What must be escaped in the route regex
	const REGEX_ESCAPE  = '[.\\+*?[^\\]${}=!|]';
	
	// What can be part of a <segment> value
	const REGEX_SEGMENT = '[^/.,;?\n]++';
	
	/**
	 *
	 * @var array 
	 */
	protected $_routes = array();
	
	/**
	 *
	 * @var string 
	 */
	protected $_matched_route = NULL;

	/**
	 *
	 * @var Model_Page_Front 
	 */
	protected $_page = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected $_settings = NULL;


	/**
	 *
	 * @var array 
	 */
	protected $_params = array();


	public function __construct( ) {

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
	 * @param string $uri
	 * @return \Behavior_Abstract
	 */
	public function find_route( $uri )
	{
		$this->_match_route($uri);
		return $this;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function routes()
	{
		return $this->_routes;
	}

	/**
	 * 
	 * @param string $name
	 * @return string|NULL
	 */
	public function __get($name) 
	{
		return $this->param($name);
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return string|NULL
	 */
	public function param($name, $default = NULL)
	{
		return Arr::get($this->_params, $name, $default);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function params()
	{
		return $this->_params;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public function __isset($name) 
	{
		return isset($this->_params[$name]);
	}
	
	/**
	 * 
	 * @return \Behavior_Abstract
	 * @throws Kohana_Exception
	 */
	protected function _load_settings()
	{
		if( $this->_page === NULL )
			throw new Kohana_Exception('Page must be loaded');
		
		if( $this->_settings === NULL )
		{
			$this->_settings = ORM::factory('Page_Behavior_Setting')
				->find_by_page($this->_page)
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
	 * @param string $uri
	 */
	final protected function _match_route($uri)
	{
		foreach ($this->routes() as $_uri => $params)
		{
			if( ! isset($params['method']))
			{
				$params['method'] = 'execute';
			}

			// The URI should be considered literal except for keys and optional parts
			// Escape everything preg_quote would escape except for : ( ) < >
			$expression = preg_replace('#'.self::REGEX_ESCAPE.'#', '\\\\$0', $_uri);

			// Insert default regex for keys
			$expression = str_replace(array('<', '>'), array('(?P<', '>'.self::REGEX_SEGMENT.')'), $expression);
			
			if ( isset($params['regex']) )
			{
				$search = $replace = array();
				foreach ($params['regex'] as $key => $value)
				{
					$search[]  = "<$key>".Route::REGEX_SEGMENT;
					$replace[] = "<$key>$value";
				}

				// Replace the default regex with the user-specified regex
				$expression = str_replace($search, $replace, $expression);
			}

			if ( ! preg_match('#^'.$expression.'$#uD', $uri, $matches))
				continue;

			foreach ($matches as $key => $value)
			{
				if (is_int($key))
				{
					// Skip all unnamed keys
					continue;
				}

				// Set the value for all matched keys
				$this->_params[$key] = $value;
			}
			
			$this->_matched_route = $_uri;
			$this->{$params['method']}();
			
			return;
		}
		
		$this->_params = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);		
		
		$this->execute();
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

		return View::factory('behavior/' . $this->_page->behavior_id)
			->set('settings', $this->_settings)
			->set('behavior', $this)
			->set('page', $this->_page);
	}

	abstract public function execute();
}