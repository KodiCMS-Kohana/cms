<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Hybrid_Document extends Controller_System_Datasource_Document
{
	protected function _load_template($doc) 
	{
		$this->template->content = View::factory('datasource/'.$this->section()->type().'/document/edit')
			->set(array(
				'fields' => $this->section()->record()->fields(),
				'action' => $this->request->action()
			));
	
		View::set_global(array(
			'form' => array(
				'label_class' => 'control-label col-md-2 col-sm-3',
				'input_container_class' => 'col-md-10 col-lg-10 col-sm-9',
				'input_container_offset_class' => 'col-md-offset-2 col-sm-offset-3 col-md-10 col-sm-9'
			),
			'doc' => $doc,
			'ds' => $this->section(),
		));
	}
	
	protected function _load_session_data($doc)
	{
		return parent::_load_session_data($doc)->convert_values();
	}
	
}