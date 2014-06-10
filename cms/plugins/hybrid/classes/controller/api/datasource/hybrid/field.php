<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Api_Datasource_Hybrid_Field extends Controller_System_API
{
	public function rest_delete()
	{
		$ids = $this->param('field', array(), TRUE);
		
		$fields = DataSource_Hybrid_Field_Factory::get_fields($ids);
		
		$removed_ids = array();
		foreach($fields as $id => $field)
		{
			$field->remove();
			$removed_ids[] = $id;
		}

		$this->message('Fields has been removed!');
		$this->response($removed_ids);
	}
}