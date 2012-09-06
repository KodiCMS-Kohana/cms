<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class URL extends Kohana_URL {
	
	/**
	 * Fetches an absolute site URL based on a URI segment.
	 *
	 *     echo URL::site('foo/bar');
	 *
	 * @param   string  $uri        Site URI to convert
	 * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
	 * @param   boolean $index		Include the index_page in the URL
	 * @return  string
	 * @uses    URL::base
	 */
	public static function site( $uri = '', $protocol = NULL, $index = TRUE )
	{
		if ( 
				is_object( Request::current() )
			AND 
				Text::starts_with( Request::current()->uri(), 'admin' )
			AND !Text::starts_with( trim($uri, '/'), 'admin' ))
		{
			
			$uri = 'admin/' . ltrim( $uri, '/');
		}

		// Chop off possible scheme, host, port, user and pass parts
		$path = preg_replace( '~^[-a-z0-9+.]++://[^/]++/?~', '', trim( $uri, '/' ) );

		if ( !UTF8::is_ascii( $path ) )
		{
			// Encode all non-ASCII characters, as per RFC 1738
			$path = preg_replace( '~([^/]+)~e', 'rawurlencode("$1")', $path );
		}

		// Concat the URL
		return URL::base( $protocol, $index ) . $path;
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

		if ( strpos( $current, $uri ) === 0 )
		{
			return TRUE;
		}

		return FALSE;
	}

}

// End url