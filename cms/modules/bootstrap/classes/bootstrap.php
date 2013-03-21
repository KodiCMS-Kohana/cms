<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Twitter bootstrap/UI
 */

class Bootstrap {
	
	public static function HTML( $string )
	{
		return Bootstrap_Helper_HTML::factory(array(
			'string' => (string) $string
		));
	}
}