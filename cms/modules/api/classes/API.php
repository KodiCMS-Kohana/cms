<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class API {
	
	const NO_ERROR = 200;
	const ERROR_MISSING_PAPAM = 110;
	const ERROR_VALIDATION = 120;
	const ERROR_UNKNOWN = 130;
	const ERROR_TOKEN = 140;
	const ERROR_PERMISSIONS = 220;
	const ERROR_PAGE_NOT_FOUND = 404;
	
	protected static function _get_key()
	{
		$key = Config::get('api', 'key');

		if ($key === NULL)
		{
			throw HTTP_API_Exception::factory(API::ERROR_TOKEN, 'API key not generated. Generate a new key in the site settings.');
		}

		return $key;
	}

	/**
	 * 
	 * @param string $uri
	 * @param array $params
	 * @param boolean $cache
	 * @return API_Response
	 */
	public static function get($uri, array $params = array(), $cache = FALSE)
	{
		$request = static::request($uri, $cache)
			->method(Request::GET)
			->query($params)
			->query('api_key', self::_get_key())
			->execute();
		
		return static::response($request);
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param array $params
	 * @param boolean $cache
	 * @return API_Response
	 */
	public static function put($uri, array $params = array(), $cache = FALSE)
	{
		$request = static::request($uri, $cache)
			->method(Request::PUT)
			->post($params)
			->post('api_key', self::_get_key())
			->execute();
		
		return static::response($request);
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param array $params
	 * @param boolean $cache
	 * @return API_Response
	 */
	public static function post($uri, array $params = array(), $cache = FALSE)
	{
		$request = static::request($uri, $cache)
			->method(Request::POST)
			->post($params)
			->post('api_key', self::_get_key())
			->execute();
		
		return static::response($request);
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param array $params
	 * @param boolean $cache
	 * @return API_Response
	 */
	public static function delete($uri, array $params = array(), $cache = FALSE)
	{
		$request = static::request($uri, $cache)
			->method(Request::DELETE)
			->post($params)
			->post('api_key', self::_get_key())
			->execute();
		
		return static::response($request);
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param boolean $cache
	 * @return Request
	 */
	public static function request($uri, $cache = FALSE)
	{
		if (strpos($uri, '-') === FALSE)
		{
			$uri = '-' . $uri;
		}
		else if (strpos($uri, '-') > 0 AND strpos($uri, '/') === FALSE)
		{
			$uri = '/' . $uri;
		}

		if (IS_BACKEND)
		{
			$uri = ADMIN_DIR_NAME . '/api' . $uri;
		}
		else
		{
			$uri = 'api' . $uri;
		}

		$params = array();
		if ($cache !== FALSE)
		{
			$params['cache'] = HTTP_Cache::factory(Cache::instance());
		}

		return Request::factory($uri, $params);
	}
	
	/**
	 * 
	 * @param Response $response
	 * @return \API_Response
	 */
	protected static function response(Response $response)
	{
		return new API_Response($response);
	}
}