<?php defined('SYSPATH') OR die('No direct script access.');


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
}
