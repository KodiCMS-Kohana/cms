<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Hybrid_Document extends Controller_System_Datasource_Document
{
	protected function _load_template($doc) 
	{
		parent::_load_template($doc);
		
		$this->template->content->set(array(
			'fields' => $this->section()->record()->fields()
		));
	}
	
	protected function _load_session_data($doc)
	{
		return parent::_load_session_data($doc)->convert_values();
	}
	
}