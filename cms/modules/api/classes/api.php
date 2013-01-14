<?php defined('SYSPATH') or die('No direct script access.');

class API {
	
	const ERROR_MISSING_PAPAM = 110;
	const ERROR_PERMISSIONS = 220;
	const ERROR_PAGE_NOT_FOUND = 404;
	
	public static function get($uri, array $params = array())
	{
		$request = static::request($uri)
			->method(Request::GET)
			->query($params)
			->execute();
		
		return static::response($request);
	}
	
	public static function put($uri, array $params = array())
	{
		$request = static::request($uri)
			->method(Request::PUT)
			->post($params)
			->execute();
		
		return static::response($request);
	}
	
	public static function post($uri, array $params = array())
	{
		$request = static::request($uri)
			->method(Request::POST)
			->post($params)
			->execute();
		
		return static::response($request);
	}
	
	public static function delete($uri, array $params = array())
	{
		$request = static::request($uri)
			->method(Request::DELETE)
			->post($params)
			->execute();
		
		return static::response($request);
	}
	
	public static function request($uri)
	{
		return Request::factory('api/' . $uri);
	}
	
	protected static function response(Request $request)
	{
		return $request->body();
	}
}