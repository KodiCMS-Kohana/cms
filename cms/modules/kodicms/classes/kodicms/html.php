<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Kodicms_HTML extends Kohana_HTML {

	public static function attributes(array $attributes = NULL)
	{
		if (empty($attributes))
			return '';

		foreach ($attributes as $key => $value)
		{
			if(is_array($value))
			{
				$attributes[$key] = implode(' ', $value);
			}
		}

		return parent::attributes($attributes);
	}
	
	/**
	 * Generate an ordered list of items.
	 *
	 * @param  array   $list
	 * @param  array   $attributes
	 * @return string
	 */
	public static function ol(array $list, array $attributes = NULL)
	{
		return self::_listing('ol', $list, $attributes);
	}
	
	/**
	 * Generate an un-ordered list of items.
	 *
	 * @param  array   $list
	 * @param  array   $attributes
	 * @return string
	 */
	public static function ul(array $list, array $attributes = NULL)
	{
		return self::_listing('ul', $list, $attributes);
	}
	
	/**
	 * Create a listing HTML element.
	 *
	 * @param  string  $type
	 * @param  array   $list
	 * @param  array   $attributes
	 * @return string
	 */
	protected static function _listing($type, array $list, array $attributes = NULL)
	{
		$html = '';

		if (count($list) == 0) return $html;

		// Essentially we will just spin through the list and build the list of the HTML
		// elements from the array. We will also handled nested lists in case that is
		// present in the array. Then we will build out the final listing elements.
		foreach ($list as $key => $value)
		{
			if (is_array($value))
			{
				$html .= self::_nested_listing($key, $type, $value);
			}
			else
			{
				$html .= '<li>'.$value.'</li>';
			}
		}

		return "<{$type}" . HTML::attributes($attributes) . ">{$html}</{$type}>";
	}
	
	/**
	 * Create the HTML for a nested listing attribute.
	 *
	 * @param  mixed    $key
	 * @param  string  $type
	 * @param  string  $value
	 * @return string
	 */
	protected static function _nested_listing($key, $type, $value)
	{
		if (is_int($key))
		{
			return self::_listing($type, $value);
		}
		else
		{
			return '<li>'.$key.self::_listing($type, $value).'</li>';
		}
	}
}
