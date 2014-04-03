<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Api_Datasource_Hybrid_Field extends Controller_System_API
{
	public function rest_delete()
	{
		$ds_id = (int) $this->param('ds_id', NULL, TRUE);
		
		$fields = $this->param('field', array(), TRUE);
		
		$ds = Datasource_Data_Manager::load($ds_id);
		DataSource_Hybrid_Field_Factory::remove_fields($ds->record(), $fields);
		
		$this->response($fields);
	}
}