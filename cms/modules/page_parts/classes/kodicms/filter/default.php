<?php defined('SYSPATH') or die('No direct access allowed.');

class KodiCMS_Filter_Default extends Filter_Decorator {
	
	/**
	 * @param string $text
	 * @return string
	 */
	public function apply($text)
	{
		return $text;
	}
}