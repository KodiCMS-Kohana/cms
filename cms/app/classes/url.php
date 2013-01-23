<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class URL extends Kohana_URL {
	
	/**
	 * 
	 * @param string $uri
	 * @param string $suffix
	 * @return boolean
	 */
	public static function check_suffix($uri, $suffix = NULL)
	{
		if($suffix === NULL)
		{
			$suffix = URL_SUFFIX;
		}
		
		return !(strstr($uri, $suffix) === FALSE);
	}
	
	public static function backend($uri = '', $protocol = NULL, $index = TRUE)
	{
		$uri = ADMIN_DIR_NAME . '/' . ltrim( $uri, '/');
		return parent::site($uri, $protocol, $index);
	}

	public static function site( $uri = '', $protocol = NULL, $index = TRUE )
	{
		$is_backend = NULL;
		
		if( defined( 'REST_BACKEND' )) 
		{
			$is_backend = REST_BACKEND;
		}
		else if( defined( 'IS_BACKEND' ) )
		{
			$is_backend = IS_BACKEND;
		}
		
		if( $is_backend !== NULL ) 
		{
			if ( $is_backend AND IS_INSTALLED AND !URL::match( ADMIN_DIR_NAME, $uri ) )
			{
				return URL::backend($uri);
			}
			else if( $is_backend === FALSE AND ! URL::check_suffix( $uri, '.' ))
			{
				if(!empty($uri))
				{
					$uri .= URL_SUFFIX;
				}
			}
		}

		return parent::site($uri, $protocol, $index);
	}

	/**
	 * 
	 * @param string $uri
	 * @param string $current
	 * @return boolean
	 */
	public static function match( $uri, $current = NULL )
	{
		$uri = trim( $uri, '/' );

		if ( $current === NULL AND Request::current() )
		{
			$current = Request::current()->uri();
		}

		$current = trim( $current, '/' );

		if ( $current == $uri )
		{
			return TRUE;
		}
		
		if(empty($uri))
		{
			return FALSE;
		}

		if ( strpos( $current, $uri ) !== FALSE )
		{
			return TRUE;
		}

		return FALSE;
	}

}

// End url