<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Navigation
 */

class Model_Navigation_Abstract {
	
	/**
	 *
	 * @var array
	 */
	protected $_params = array(
		'counter' => 0,
		'permissions' => NULL
	);


	/**
	 * 
	 * @param array $data
	 */
	public function __construct(array $data = array())
	{
		foreach ( $data as $key => $value )
		{
			$this->{$key} = $value;
		}
	}
	
	/**
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		return $this->get($name);
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get( $name, $default = NULL )
	{
		return Arr::get($this->_params, $name, $default);
	}
	
	public function __set( $name, $value )
	{
		$this->_params[$name] = $value;
		return $this;
	}
	
	public function __isset($name) 
	{
		return isset($this->_params[$name]);
	}

	/**
	 * 
	 * @return boolean
	 */
	public function is_active()
	{
		return (bool) Arr::get($this->_params, 'is_active', FALSE);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function name()
	{
		return __(Arr::get($this->_params, 'name'));
	}
	
	/**
	 * 
	 * @return string
	 */
	public function url()
	{
		return Arr::get($this->_params, 'url');
	}
	
	public function counter()
	{
		return (int) Arr::get($this->_params, 'counter');
	}
	
	/**
	 * 
	 * @param boolean $status
	 * @return \Model_Navigation_Abstract
	 */
	public function set_active($status = TRUE)
	{
		$this->_params['is_active'] = (bool) $status;
		
		return $this;
	}
}