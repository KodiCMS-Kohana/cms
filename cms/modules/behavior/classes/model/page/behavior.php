<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Page_Behavior extends Model_Page_Front {

	/**
	 * 
	 * @param string $part
	 * @param boolean $inherit
	 * @param integer $cache_lifetime
	 */
	public function content($part = 'body', $inherit = FALSE, $cache_lifetime = NULL)
	{
		$method = 'content_' . URL::title($part, '_');
		if(method_exists($this, $method))
		{
			return $this->{$method}($cache_lifetime);
		}
		
		return parent::content($part, $inherit, $cache_lifetime);
	}
	
	/**
	 * 
	 * @param string $part
	 * @param Model_Page_Part $data
	 * @return \Model_Page_Front
	 */
	public function set_part($part, Model_Page_Part $data)
	{
		$this->_parts[$part] = $data;
		
		return $this;
	}
}