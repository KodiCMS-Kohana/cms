<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Api_Datasource_Hybrid_Field extends Controller_System_API
{
	public function rest_delete()
	{
		$ids = $this->param('field', array(), TRUE);
		
		DataSource_Hybrid_Field_Factory::remove_fields_by_id($ids);

		$this->message('Fields has been removed');
		$this->response($ids);
	}
	
	public function post_headline()
	{
		$field = $this->_get_field();

		$old_field = clone($field);
		$field->set_in_headline(1);

		DataSource_Hybrid_Field_Factory::update_field($old_field, $field);
		$this->message('Field ":field" added to headline', array(':field' => $field->header));
	}
	
	public function delete_headline()
	{
		$field = $this->_get_field();
		$old_field = clone($field);
		$field->set_in_headline(0);
		DataSource_Hybrid_Field_Factory::update_field($old_field, $field);

		$this->message('Field ":field" removed from headline', array(':field' => $field->header));
	}
	
	public function post_index_type()
	{
		$field = $this->_get_field();
		
		if($field->is_indexable() OR $field->index_type !== NULL)
		{
			$old_field = clone($field);
			$field->set_index();
			DataSource_Hybrid_Field_Factory::update_field($old_field, $field);
			DataSource_Hybrid_Field_Factory::alter_table_field_add_index($field);

			$this->message('Index to field ":field" added', array(':field' => $field->header));
		}
		else
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Field cannot be indexed');
		}
	}
	
	public function delete_index_type()
	{
		$field = $this->_get_field();

		if($field->is_indexable() OR $field->index_type === NULL)
		{
			$old_field = clone($field);
			$field->set_index(NULL);
			DataSource_Hybrid_Field_Factory::update_field($old_field, $field);
			DataSource_Hybrid_Field_Factory::alter_table_field_drop_index($field);

			$this->message('Index field ":field" dropped', array(':field' => $field->header));
		}
		else
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Field cannot be indexed');
		}
	}
	
	protected function _get_field()
	{
		$field_id = $this->param('id', NULL, TRUE);
		
		$field = DataSource_Hybrid_Field_Factory::get_field($field_id);
		
		if($field === NULL)
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Field not found!');
		}
		
		return $field;
	}
}