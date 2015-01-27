<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class KodiCMS_Filter_Decorator {

	/**
	 * @param string $text
	 * @return string
	 */
	abstract public function apply($text);
}
