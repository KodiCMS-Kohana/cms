<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Text extends Kohana_Text 
{
	/**
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function starts_with( $haystack, $needle )
	{
		$length = strlen( $needle );
		return (substr( $haystack, 0, $length ) === $needle);
	}

	/**
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function ends_with( $haystack, $needle )
	{
		$length = strlen( $needle );
		if ( $length == 0 )
		{
			return true;
		}

		return (substr( $haystack, -$length ) === $needle);
	}

}

// End text
