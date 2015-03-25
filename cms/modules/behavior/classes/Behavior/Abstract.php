<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Behavior
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
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
	protected $_config = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_settings = NULL;

	/**
	 * 
	 * @param array $config
	 */
	public function __construct(array $config = array()) 
	{
		$this->_config = $config;

		$routes = $this->routes();

		if (isset($this->_config['routes']) AND is_array($this->_config['routes']))
		{
			$routes = $this->_config['routes'] + $routes;
		}

		$this->_router = new Behavior_Route($routes);
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

		if (strpos($method, '::') !== FALSE)
		{
			Callback::invoke($method, array($this));
		}
		else
		{
			$this->{$method}();
		}

		return $this;
	}

	/**
	 * 
	 * @param Model_Page_Front $page
	 * @return \Behavior_Abstract
	 */
	public function set_page(Model_Page_Front &$page)
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
	 * @return Behavior_Settings
	 */
	public function settings()
	{
		if ($this->_settings === NULL)
		{
			$this->_settings = new Behavior_Settings($this->page());
		}

		return $this->_settings;
	}

	public function stub()
	{
		
	}
	
	abstract public function execute();
}