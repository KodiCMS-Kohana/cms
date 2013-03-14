<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/components.html#navbar
 * @package    Twitter bootstrap/UI
 */
class UI_Navigation {
	
	const DIVIDER = 'divider-vertical';
	
	public static function brand( $title, $url = '#', $attributes = array() )
	{	
		if(isset($attributes['class']))
			$attributes['class'] .= ' brand';
		else
			$attributes['class'] = ' brand';

		return HTML::anchor($url, $title, $attributes);
	}
	
	public static function divider()
	{
		return '<li'.HTML::attributes(array('class' => UI_Navigation::DIVIDER)).'></li>';
	}
}