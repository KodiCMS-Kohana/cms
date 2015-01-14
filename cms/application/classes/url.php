<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class URL extends Kohana_URL {
	
	/**
	 * 
	 * @param string $uri
	 * @param string $protocol
	 * @param boolean $index
	 * @return string
	 */
	public static function backend($uri = '', $protocol = NULL, $index = TRUE)
	{
		if (!URL::has_segment(ADMIN_DIR_NAME, $uri))
		{
			$uri = ADMIN_DIR_NAME . '/' . ltrim($uri, '/');
		}

		return parent::site($uri, $protocol, $index);
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param string $protocol
	 * @param boolean $index
	 * @return string
	 */
	public static function frontend($uri = '', $protocol = NULL, $index = TRUE)
	{
		$uri_components = parse_url($uri);
		
		$hash = $query_string = '';
		if (strpos($uri, '#') !== FALSE)
		{
			list($uri, $hash) = preg_split('/#/', $uri);
			$hash = '#' . $hash;
		}
		
		if (strpos($uri, '?') !== FALSE)
		{
			list($uri, $query_string) = preg_split('/\?/', $uri);
			$query_string = '?' . $query_string;
		}

		if (IS_INSTALLED AND ! empty($uri) AND $uri != '/' AND ! URL::has_suffix($uri))
		{
			$uri = rtrim($uri, '/') . URL_SUFFIX . $query_string . $hash;
		}
		
		unset($hash, $query_string);

		return parent::site($uri, $protocol, $index);
	}

	/**
	 * 
	 * @param string $uri
	 * @param string $current
	 * @return boolean
	 */
	public static function match($uri, $current = NULL)
	{
		$uri = trim($uri, '/');

		if ($current === NULL AND Request::current())
		{
			$current = Request::current()->uri();
		}

		$current = trim($current, '/');

		if ($current == $uri)
		{
			return TRUE;
		}

		if (empty($uri))
		{
			return FALSE;
		}

		if (strpos($current, $uri) !== FALSE)
		{
			return TRUE;
		}

		return FALSE;
	}
	
	/**
	 *
	 * @param string $segment
	 * @param string $current
	 * @return boolean
	 */
	public static function has_segment($segment, $current = NULL)
	{
		$segment = trim($segment, '/');
		
		if ($current === NULL AND Request::current())
		{
			$current = Request::current()->uri();
		}

		$current = trim($current, '/');

		if (empty($current))
		{
			return FALSE;
		}
		
		if ($segment == $current)
		{
			return TRUE;
		}
		
		$segments = explode('/', $current);
		
		foreach ($segments as $_segment)
		{
			if ($segment == $_segment)
			{
				return TRUE;
			}
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param string $suffix
	 * @return boolean
	 */
	public static function has_suffix($uri, $suffix = NULL)
	{
		$ext = pathinfo($uri, PATHINFO_EXTENSION);

		if (!empty($ext))
		{
			return TRUE;
		}

		if ($suffix === NULL AND defined('URL_SUFFIX') AND strlen($suffix) > 0)
		{
			$suffix = URL_SUFFIX;
		}

		return !(strstr($uri, $suffix) === FALSE);
	}
}