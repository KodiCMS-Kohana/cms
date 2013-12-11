<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Validation extends Kohana_Validation {
	
	public function offsetExists($offset)
	{
		$offset = str_replace('.*', '', $offset);
		return Arr::path($this->_data, $offset, '!isset') != '!isset';
	}
	
	public function offsetGet($offset)
	{
		return Arr::path($this->_data, $offset);
	}
}