<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Navigation
 */

class Model_Navigation_Page extends Model_Navigation_Abstract {
	
	/**
	 *
	 * @var Model_Navigation_Section 
	 */
	protected $section = NULL;

	/**
	 * 
	 * @param Model_Navigation_Section $section
	 * @return \Model_Navigation_Page
	 */
	public function set_section( Model_Navigation_Section $section)
	{
		$this->section = $section;
		return $this;
	}
	
	/**
	 * 
	 * @return Model_Navigation_Section
	 */
	public function get_section()
	{
		return $this->section;
	}

	/**
	 * 
	 * @param boolean $status
	 * @return \Model_Navigation_Page
	 */
	public function set_active($status = TRUE)
	{
		parent::set_active($status);
		$this->section->set_active($status);
		
		return $this;
	}
}