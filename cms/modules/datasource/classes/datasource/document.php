<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Datasource_Document {

	/**
	 *
	 * @var integer
	 */
	public $id = NULL;
	
	/**
	 *
	 * @var integer 
	 */
	public $ds_id;
	
	/**
	 *
	 * @var boolean
	 */
	public $published = FALSE;
	
	/**
	 *
	 * @var string
	 */
	public $header;
	
	/**
	 *
	 * @var array
	 */
	public $fields = array();
	
	public function __construct()
	{
		$this->fields = array();
	}
	
	public function loaded()
	{
		return $this->id !== NULL;
	}

	/**
	 * 
	 * @param array $array
	 * @return Datasource_Document
	 */
	public function read_values($array) 
	{
		if( ! $this->loaded() )
		{
			$this->id = (int) Arr::get($array, 'id');
			$this->ds_id = (int) Arr::get($array, 'ds_id');
		}

		$this->published = Arr::get($array, 'published', FALSE) ? TRUE : FALSE;
		$this->header = Arr::get($array, 'header');

		foreach($this->fields as $k => $v)
		{
			if(isset($array[$k]))
			{
				$this->fields[$k] = $array[$k];
			}
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return DataSource_Data_Hybrid_Field
	 */
	function get_field($name) 
	{
		return Arr::get($this->fields, $name);
	}
}