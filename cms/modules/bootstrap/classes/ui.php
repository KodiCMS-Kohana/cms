<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class UI {
	
	const BUTTON_TYPE_BUTTON = 0;
	const BUTTON_TYPE_ANCHOR = 1;
	
	public static function icon( $name )
	{
		return '<i class="icon-' . HTML::chars( $name ) .'"></i>';
	}
	
	public static function label( $text, $type = 'info' )
	{
		return '<span class="label label-' . $type . '">' . $text . '</span>';
	}
	
	public static function badge( $text, $type = 'info' )
	{
		return '<span class="badge badge-' . $type . '">' . $text . '</span>';
	}

	public static function button($body, array $attributes = NULL)
	{
		if(!isset($attributes['class']))
		{
			$attributes['class'] = 'btn';
		}
		
		if(isset($attributes['icon']))
		{
			$body = $attributes['icon'] . ' '.$body;
			unset($attributes['icon']);
		}
		
		if(isset($attributes['href']))
		{
			$attributes['type'] = self::BUTTON_TYPE_ANCHOR;
			
			$href = $attributes['href'];
			unset($attributes['href']);
		}
		elseif(isset($attributes['name']))
		{
			$attributes['type'] = self::BUTTON_TYPE_BUTTON;
		}
		
		if(!isset($attributes['type']))
		{
			$attributes['type'] = self::BUTTON_TYPE_BUTTON;
		}
		
		$type = $attributes['type'];
		unset($attributes['type']);
		
		switch ($type) {
			case self::BUTTON_TYPE_ANCHOR:
				
				return HTML::anchor($href, $body, $attributes);
				break;
			default:
				return '<button'.HTML::attributes($attributes).'>'.$body.'</button>';
				break;
		}
	}
	
	public static function actions($page) 
	{
		return
			UI::button(__('Save and Continue editing'), array(
				'class' => 'btn btn-large', 'icon' => UI::icon('repeat'),
				'name' => 'continue'
			))
			. UI::button(__('Save and Close'), array(
				'class' => 'btn btn-info', 'icon' => UI::icon('ok icon-white'),
				'name' => 'commit'
			))
			. UI::button(__('Cancel'), array(
				'href' => URL::site($page), 'icon' => UI::icon('remove'),
				'class' => 'btn btn-link'
			));
	}
}