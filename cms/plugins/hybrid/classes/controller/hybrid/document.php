<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Hybrid_Document extends Controller_System_Datasource_Document
{
	protected function _load_template($doc) 
	{
		$this->template->content = View::factory('datasource/'.$this->section()->type().'/document/edit')->set( array(
			'fields' => $this->section()->record()->fields(),
			'ds' => $this->section(),
			'doc' => $doc,
			'action' => $this->request->action()
		));
	}
	
	protected function _load_session_data($doc)
	{
		return parent::_load_session_data($doc)->convert_values();
	}
	
}