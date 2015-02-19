<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Behavior
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Behavior_Route {
	
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
	 * @var array 
	 */
	protected $_params = array();
	
	/**
	 *
	 * @var string 
	 */
	protected $_matched_route = NULL;
	
	public function __construct(array $routes) 
	{
		$this->_routes = $routes;
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
	 * @return string
	 */
	public function matched_route()
	{
		return $this->_matched_route;
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
	 * @param string $uri
	 * @return \Behavior_Abstract
	 */
	public function find($uri)
	{
		$method = $this->_match_route($uri);

		Context::instance()->behavior_router($this);

		return $method;
	}

	/**
	 * 
	 * @return array
	 */
	public function routes($routes = NULL)
	{
		return $this->_routes;
	}

	/**
	 * 
	 * @param string $uri
	 */
	final protected function _match_route($uri)
	{
		$default_method = 'execute';

		foreach ($this->routes() as $_uri => $params)
		{
			if (!isset($params['method']))
			{
				$params['method'] = $default_method;
			}

			$expression = Route::compile($_uri, Arr::get($params, 'regex'));
			if (!preg_match($expression, $uri, $matches))
			{
				continue;
			}

			foreach ($matches as $key => $value)
			{
				if (is_int($key))
				{
					// Skip all unnamed keys
					continue;
				}

				// Set the value for all matched keys
				$this->_params[$key] = $value;
				Context::instance()->set('.' . $key, $value);
			}

			$this->_matched_route = $_uri;

			return $params['method'];
		}

		$this->_params = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);

		return $default_method;
	}
}