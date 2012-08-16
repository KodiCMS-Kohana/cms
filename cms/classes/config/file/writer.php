<?php defined('SYSPATH') or die('No direct access allowed.');


class Config_File_Writer extends Config_File_Reader implements Config_Writer {
	
	protected $_current_group = NULL;
	protected $_config = array();



	public function load($group)
	{
		$this->_current_group = $group;
		$this->_config = parent::load($group);
		
		return $this;
	}
	
	/**
	 * Get a variable from the configuration or return the default value.
	 *
	 *     $value = $config->get($key);
	 *
	 * @param   string   array key
	 * @param   mixed    default value
	 * @return  mixed
	 */
	public function get($key, $default = NULL)
	{
		return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
	}


	public function __set($key, $config)
	{
		return $this->set($key, $config);
	}
	
	public function set($key, $value)
	{
		$this->write($this->_current_group, $key, $value);
		
		return $this;
	}

	public function write($group, $key, $config) {
		echo 'write data';
	}
}