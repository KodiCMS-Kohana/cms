<?php defined('SYSPATH') or die('No direct access allowed.');

class HTML extends Kohana_HTML {

	
	public static function icon( $name )
	{
		$class = array();

		$class[] = 'icon-' . HTML::chars( $name );
		return '<i class="'.implode(' ', $class).'"></i>';
	}
	
	public static function label( $text, $type = 'info' )
	{
		return '<span class="label label-' . $type . '">' . $text . '</span>';
	}


	public static function button( $uri, $title, $icon = NULL, $type = 'btn')
	{
		$attributes = array();
		if($icon !== NULL)
		{
			$icon = self::icon($icon);

			$title = $icon . ' ' . $title;
		}
		
		if($type !== NULL)
		{
			if(is_array($type))
			{
				$type = implode(' ', $type);
			}
		}
		
		$attributes['class'] = $type;

		return self::anchor($uri, $title, $attributes);
	}

} // End html
