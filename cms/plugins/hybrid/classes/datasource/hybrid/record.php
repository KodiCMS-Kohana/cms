<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package Datasource
 * @category Hybrid
 */
class DataSource_Hybrid_Record {
	
	/**
	 * Объект раздела
	 * @var DataSource_Section_Hybrid
	 */
	protected $_datasource;
	
	/**
	 * Идентификатор раздела
	 * @var integer
	 */
	protected $_ds_id;
	
	/**
	 * Поля раздела
	 * 
	 * @see DataSource_Hybrid_Record::fields()
	 * 
	 * @var array array([Field name] => DataSource_Hybrid_Field, ....)
	 */
	protected $_fields = NULL;
	
	/**
	 * 
	 * @param Datasource_Document $datasource
	 */
	public function __construct( Datasource_Section $datasource)
	{
		$this->_datasource = $datasource;
		$this->_ds_id = (int) $datasource->id();
	}
	
	/**
	 * Получение идентификатора раздела
	 * @return integer
	 */
	public function ds_id()
	{
		return $this->_ds_id;
	}

	
	
	/**
	 * Получение списка полей раздела
	 * 
	 * @return array array([Field name] => DataSource_Hybrid_Field, ....)
	 */
	public function fields()
	{
		if($this->_fields === NULL)
		{
			$this->_load();
		}

		return $this->_fields;
	}
	
	/**
	 * Загрузка полей раздела из БД
	 * 
	 * @return \DataSource_Hybrid_Record
	 */
	protected function _load() 
	{
		$this->_fields = array();
		
		$fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id());
		
		foreach ($fields as $field)
		{
			$this->_fields[$field->name] = $field;
		}
		
		return $this;
	}

	/**
	 * Удаление полей раздела
	 * 
	 * @see DataSource_Section_Hybrid::remove()
	 * 
	 * @return \DataSource_Hybrid_Record
	 */
	public function destroy() 
	{
		DataSource_Hybrid_Field_Factory::remove_fields($this, array_keys($this->fields()));
		return $this;
	}
}