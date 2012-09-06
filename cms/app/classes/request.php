<?php defined('SYSPATH') or die('No direct access allowed.');

class Request extends Kohana_Request {

	public static function detect_uri()
	{
		$uri = parent::detect_uri();

		if(!defined( 'URL_SUFFIX' ))
		{
			return $uri;
		}
		else
		{
			return str_replace(URL_SUFFIX, '', $uri);
		}
	}

}