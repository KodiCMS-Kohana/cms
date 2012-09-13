<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class URL extends Kohana_URL {
	
	public static function check_suffix($uri, $suffix = NULL)
	{
		if($suffix === NULL)
		{
			$suffix = URL_SUFFIX;
		}
		
		return !(strstr($uri, $suffix) === FALSE);
	}

	public static function site( $uri = '', $protocol = NULL, $index = TRUE )
	{
		if( defined( 'IS_BACKEND' )) 
		{
			if ( IS_BACKEND AND IS_INSTALLED AND !URL::match( ADMIN_DIR_NAME, $uri ) )
			{
				$uri = ADMIN_DIR_NAME . '/' . ltrim( $uri, '/');
			}
			else if(!IS_BACKEND AND !URL::check_suffix( $uri, '.' ))
			{
				$uri .= URL_SUFFIX;
			}
		}

		return parent::site($uri, $protocol, $index);
	}


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