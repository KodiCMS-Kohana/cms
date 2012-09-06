<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Text extends Kohana_Text {

	
	public static function starts_with( $haystack, $needle )
	{
		$length = strlen( $needle );
		return (substr( $haystack, 0, $length ) === $needle);
	}

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
