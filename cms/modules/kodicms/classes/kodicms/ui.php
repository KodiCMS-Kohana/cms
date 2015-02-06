<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_UI {
	
	const BUTTON_TYPE_BUTTON = 0;
	const BUTTON_TYPE_ANCHOR = 1;

	/**
	 * 
	 * @param string $name
	 * @param array $attributes
	 * @return string HTML
	 */
	public static function icon($name, array $attributes = array())
	{
		$attributes = self::_build_attribute_class($attributes, 'fa fa-' . HTML::chars($name));
		return '<i' . HTML::attributes($attributes) . '></i>';
	}

	/**
	 * 
	 * @param string $text
	 * @param string $type
	 * @param array $attributes
	 * @return string HTML
	 */
	public static function label($text, $type = 'info', array $attributes = array())
	{
		$attributes = self::_build_attribute_class($attributes, 'label label-' . HTML::chars($type));
		return '<span' . HTML::attributes($attributes) . '>' . $text . '</span>';
	}

	/**
	 * 
	 * @param string $text
	 * @param string $type
	 * @param array $attributes
	 * @return string HTML
	 */
	public static function badge($text, $type = 'info', array $attributes = array())
	{
		$attributes = self::_build_attribute_class($attributes, 'badge badge-' . HTML::chars($type));
		return '<span' . HTML::attributes($attributes) . '>' . $text . '</span>';
	}

	/**
	 * 
	 * @param string $body
	 * @param array $attributes
	 * @return string HTML
	 */
	public static function button($body, array $attributes = NULL)
	{
		$attributes = self::_build_attribute_class($attributes, 'btn');

		if (isset($attributes['icon']))
		{
			$body = $attributes['icon'] . ' ' . $body;
			unset($attributes['icon']);
		}

		if (isset($attributes['href']))
		{
			$attributes['type'] = self::BUTTON_TYPE_ANCHOR;

			$href = $attributes['href'];
			unset($attributes['href']);
		}
		elseif (isset($attributes['name']))
		{
			$attributes['type'] = self::BUTTON_TYPE_BUTTON;
		}

		if (!isset($attributes['type']))
		{
			$attributes['type'] = self::BUTTON_TYPE_BUTTON;
		}

		$type = $attributes['type'];
		unset($attributes['type']);

		switch ($type)
		{
			case self::BUTTON_TYPE_ANCHOR:
				return HTML::anchor($href, $body, $attributes);
				break;
			default:
				return '<button' . HTML::attributes($attributes) . '>' . $body . '</button>';
				break;
		}
	}
	
	/**
	 * 
	 * @param string $title
	 * @param array $types
	 * @return string
	 */
	public static function hidden($title, array $types = array('xs', 'sm'))
	{
		$attributes = array('class' => array());

		foreach ($types as $type)
		{
			$attributes['class'][] = 'hidden-' . $type;
		}

		return '<span' . HTML::attributes($attributes) . '>' . $title . '</span>';
	}
	
	/**
	 * 
	 * @param string $title
	 * @return string
	 */
	public static function page_header($title)
	{
		return '<div class="page-header"><h1>' . $title . '</h1></div>';
	}

	/**
	 * 
	 * @param string $page
	 * @param string $uri
	 * @return string
	 */
	public static function actions($page = NULL, $uri = NULL) 
	{
		if ($uri === NULL)
		{
			$uri = Route::get('backend')->uri(array('controller' => $page));
		}

		return View::factory('ui/actions', array(
			'uri' => $uri
		));
	}
	
	/**
	 * 
	 * @param integer $num
	 * @return string
	 */
	public static function counter($num = 0)
	{
		if ($num == 0)
		{
			return '';
		}

		return '<span' . HTML::attributes(array('class' => 'counter')) . '>' . (int) $num . '</span>';
	}
	
	protected static function _build_attribute_class(array $attributes = array(), $class)
	{
		if (!isset($attributes['class']))
		{
			$attributes['class'] = array();
		}
		else if (!is_array($attributes['class']))
		{
			$attributes['class'] = explode(' ', $attributes['class']);
		}

		if (is_array($class))
		{
			foreach ($class as $class_name)
			{
				$attributes['class'][] = $class_name;
			}
		}
		else
		{
			$attributes['class'][] = $class;
		}

		$attributes['class'] = array_filter(array_unique($attributes['class']));

		return $attributes;
	}

}