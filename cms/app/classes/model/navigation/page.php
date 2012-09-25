<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Navigation_Page extends Model_Navigation_Abstract {
	
	protected $section = NULL;

	public function set_section( Model_Navigation_Section $section)
	{
		$this->section = $section;
	}
	
	
	public function get_section()
	{
		return $this->section;
	}

	public function set_active($status = TRUE)
	{
		parent::set_active($status);
		$this->section->set_active($status);
		
		return $this;
	}
}