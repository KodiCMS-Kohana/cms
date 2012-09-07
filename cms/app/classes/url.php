<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class URL extends Kohana_URL {

	public static function site( $uri = '', $protocol = NULL, $index = TRUE )
	{
		if ( 
			IS_BACKEND
		AND 
			IS_INSTALLED 
		AND 
			!URL::math( 'admin', $uri ) 
		)
		{
			$uri = 'admin/' . ltrim( $uri, '/');
		}

		return parent::site($uri, $protocol, $index);
	}


	public static function math( $uri, $current = NULL )
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

		if ( strpos( $current, $uri ) !== FALSE )
		{
			return TRUE;
		}

		return FALSE;
	}

}

// End url