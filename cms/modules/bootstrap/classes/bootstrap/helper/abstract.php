<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		Twitter Bootstrap
 * @category	Helper
 * @author		ButscHSter
 */
class Bootstrap_Helper_Abstract extends ArrayObject {

	/**
	 * 
	 * @return array
	 */
	public function as_array()
	{
		return $this->getArrayCopy();
	}
	
	/**
	 * 
	 * @param   string  $key        array key
	 * @param   mixed   $default    default value
	 * @return  mixed
	 */
	public function get($key, $default = NULL)
	{
		return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
	}
	
	/**
	 * @param   string  $key    array key
	 * @param   mixed   $value  array value
	 * @return  $this
	 */
	public function set($key, $value)
	{
		$this->offsetSet($key, $value);

		return $this;
	}
}