<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Navigation
 * @category	Model
 * @author		ButscHSter
 */
class Model_Navigation_Page extends Model_Navigation_Abstract {
	
	/**
	 *
	 * @var Model_Navigation_Section 
	 */
	protected $_section = NULL;
	
	public function __set( $name, $value )
	{
		parent::__set($name, $value);
		
		if($this->_section !== NULL)
		{
			$this->_section->update();
		}
		
		return $this;
	}

	/**
	 * 
	 * @param Model_Navigation_Section $section
	 * @return \Model_Navigation_Page
	 */
	public function set_section( Model_Navigation_Section $section)
	{
		$this->_section = $section;
		return $this;
	}
	
	/**
	 * 
	 * @return Model_Navigation_Section
	 */
	public function get_section()
	{
		return $this->_section;
	}

	/**
	 * 
	 * @param boolean $status
	 * @return \Model_Navigation_Page
	 */
	public function set_active($status = TRUE)
	{
		parent::set_active($status);
		$this->_section->set_active($status);
		
		return $this;
	}
}