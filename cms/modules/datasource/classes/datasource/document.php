<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Datasource_Document extends DataSource_Decorator {
	
	public function fields()
	{
		return array(
			'id', 
			'ds_id', 
			'published', 
			'header'
		);
	}
	
	public function defaults()
	{
		return array(
			'published' => 1
		);
	}
	
	/**
	 * Объект раздела
	 * @var DataSource_Hybrid_Section 
	 */
	protected $_section = NULL;

	/**
	 * 
	 * @param DataSource_Hybrid_Section $section
	 */
	public function __construct( DataSource_Section $section )
	{
		$this->_section = $section;
		$this->_table_name = $section->table();

		parent::__construct();
	}
	
	protected function _initialize() 
	{
		$this->ds_id = $this->_section->id();
	}

	/**
	 * Правила фильтрации полей документа
	 * @return array
	 */
	public function filters()
	{
		return array(
			'id' => array(
				array('intval')
			),
			'ds_id' => array(
				array('intval')
			),
			'published' => array(
				array(array($this, 'set_bool'))
			)
		);
	}
	
	/**
	 * Правила валидации полей документа
	 * @return type
	 */
	public function rules()
	{
		return array(
			'header' => array(
				array('not_empty')
			)
		);
	}
	
	/**
	 * Заголовки полей
	 * @return type
	 */
	public function labels()
	{
		return array(
			'id' => __('ID'),
			'header' =>  __('Header'),
			'published' => __('Published')
		);
	}

		/**
	 * Получение объекта раздела
	 * 
	 * @return DataSource_Section
	 */
	public function section()
	{
		return $this->_section;
	}
}