<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @category	Section
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Section_Hybrid extends Datasource_Section {

	/**
	 * 
	 * @return string
	 */
	public static function default_icon()
	{
		return 'table';
	}
	
	/**
	 * Таблица раздела
	 * 
	 * @var string
	 */
	protected $_ds_table = 'dshybrid';
	
	/**
	 * Тип раздела
	 * 
	 * @var string
	 */
	protected $_type = 'hybrid';
	
	/**
	 * 
	 * @var array
	 */
	protected $_widget_types = array('hybrid_headline', 'hybrid_document');
	
	/**
	 * Индексируемые поля раздела
	 * 
	 * @var array 
	 */
	public $search_index_fields = array();
	
	/**
	 * Поля используемые в качестве индентификатора при поиске
	 * 
	 * @var array 
	 */
	public $search_index_doc_id_fields = array();
	
	/**
	 * Поле описания документа в поисковом индексе
	 * 
	 * @var integer 
	 */
	public $search_intro_field = NULL;
	
	/**
	 * Шаблон формы редактирования документа
	 * 
	 * @var string 
	 */
	public $template = NULL;

	/**
	 * 
	 * @var DataSource_Hybrid_Record
	 */
	protected $_record = NULL;
	
	/**
	 *
	 * @var DataSource_Hybrid_Agent 
	 */
	protected $_agent = NULL;
	
	/**
	 * 
	 * @return DataSource_Hybrid_Record
	 */
	public function record() 
	{
		if($this->_record === NULL)
		{
			$this->_record = new DataSource_Hybrid_Record($this);
		}

		return $this->_record;
	}
	
	/**
	 * 
	 * @return DataSource_Hybrid_Agent
	 */
	public function agent() 
	{
		if($this->_agent === NULL)
		{
			$this->_agent = DataSource_Hybrid_Agent::instance($this->id());
		}

		return $this->_agent;
	}
	
	/**
	 * Создание раздела
	 * 
	 * @param array $values
	 * @return integer Идентификатор раздела
	 */
	public function create( array $values )
	{
		$id = parent::create($values);
		
		DataSource_Hybrid_Factory::create($this);

		return $id;
	}
	
	/**
	 * Получение списка полей раздела
	 * 
	 * @return array array([Field ID] => [Field Header], ....)
	 */
	public function record_fields_array( )
	{
		$fields = array();

		foreach( $this->record()->fields() as $field)
		{
			$fields[$field->id] = $field->header;
		}
		
		return $fields;
	}
	
	/**
	 * Сохранение раздела
	 * 
	 * @param array $values
	 * @throws Validation_Exception
	 * @return boolean
	 */
	public function values(array $values = array())
	{		
		parent::values($values);
		
		$this->template = Arr::get($values, 'template');
		
		$this->search_intro_field = Arr::get($values, 'search_intro_field');
		unset($values['search_intro_field']);
		
		$this->search_index_fields = (array) Arr::get($values, 'search_index_fields', array());
		unset($values['search_index_fields']);
		
		$this->search_index_doc_id_fields = (array) Arr::get($values, 'search_index_doc_id_fields', array());
		unset($values['search_index_doc_id_fields']);
	}

	/**
	 * Удаление раздела
	 * 
	 * @return \DataSource_Hybrid_Section
	 */
	public function remove() 
	{
		$id = $this->id();

		parent::remove();
		
		$this->record()->destroy();
		DataSource_Hybrid_Factory::remove($id);
		
		$this->_record = NULL;
		$this->_agent = NULL;

		return $this;
	}
	
	/**
	 * Загрузка документов раздела в формате для индексации
	 * 
	 * В этом методе происходит загрукзка индексируемых полей документа 
	 * + поля описания документа
	 * 
	 * @param array $id
	 * @return array array([ID] => array('id', 'header', 'content', 'intro', ....), ...)
	 */
	public function get_indexable_documents( array $id = NULL ) 
	{
		$result = array();

		if( ! empty($this->search_intro_field))
		{
			$this->search_index_fields[] = $this->search_intro_field;
		}
		
		$this->search_index_fields = array_unique($this->search_index_fields);
		
		$fields = $this->search_index_fields;
		
		if( ! empty($this->search_index_doc_id_fields) )
		{
			foreach ($this->search_index_doc_id_fields as $field)
			{
				$fields[] = $field;
			}
		}

		$agent = DataSource_Hybrid_Agent::instance($this->id(), $this->id());
		
		$query = $agent->get_query_props(array_unique($fields));
		
		if(is_array($id) AND !empty($id))
		{
			$query->where('d.id', 'in', $id);
		}
		else if(!empty($id))
		{
			$query->where('d.id', '=', (int) $id);
		}
		
		$rows = $query->execute()->as_array();
	
		foreach ($rows as $row)
		{
			$doc_id = $row['id'];
			$result[$doc_id] = array(
				'id' => $row['id'],
				'intro' => Arr::get($row, $this->search_intro_field),
				'header' => $row['header']
			);
			
			$content = '';
			$params = array();
			
			foreach ($this->search_index_fields as $field)
			{
				$content .= ' ' . (string) Arr::get($row, $field);
			}
			
			foreach ($this->search_index_doc_id_fields as $field)
			{
				$field_name = DataSource_Hybrid_Field_Factory::get_field_key($field);
				
				if(empty($field_name)) continue;
				$params[$field_name] = Arr::get($row, $field);
			}
			
			$result[$doc_id]['content'] = $content;
			$result[$doc_id]['params'] = $params;
		}
	
		return $result;
	}
	
	/**
	 * Удаление документов по ID
	 * 
	 * @see DataSource_Hybrid_Document::remove()
	 * 
	 * @param array $ids
	 * @return \DataSource_Hybrid_Section
	 */
	public function remove_documents( array $ids = NULL  ) 
	{
		if (empty($ids))
		{
			return $this;
		}
		
		$deleted_documents = array();

		foreach ($ids as $id)
		{
			try
			{
				$document = $this->get_document($id);
				if($document->loaded())
				{
					$this->record()->destroy_document($document);
					$document->remove();
					
					$deleted_documents[] = $id;
				}
			}
			catch (DataSource_Exception_Document $ex)
			{
				$document->onRemoveException($ex);
				continue;
			}
		}

		$this->update_size();
		$this->remove_from_index($deleted_documents);
		$this->clear_cache();

		return $this;
	}
	
	/**
	 * Получение полного пути до файла шаблона
	 * 
	 * @return string
	 */
	public function template()
	{
		$snippet = new Model_File_Snippet($this->template);
		
		$template = NULL;

		if( $snippet->is_exists() )
		{
			$template = $snippet->get_file();
		}
		else if(($template = $snippet->find_file()) === FALSE)
		{
			$template = NULL;
		}
		
		return $template;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function acl_actions()
	{
		$actions = parent::acl_actions();
		
		$actions[] = array(
			'action' => 'field.create',
			'description' => 'Create hybrid fields'
		);
		
		$actions[] = array(
			'action' => 'field.edit',
			'description' => 'Edit hybrid fields'
		);
	
		$actions[] = array(
			'action' => 'field.remove',
			'description' => 'Remove hybrid fields'
		);
		
		return $actions;
	}

	protected function _serialize()
	{
		$vars = parent::_serialize();

		unset(
			$vars['_agent'], 
			$vars['_record'],
			$vars['indexed_doc_query']
		);
		
		return $vars;
	}
	
	protected function _initialize()
	{
		parent::_initialize();
		
		$this->_record = NULL;
		$this->_agent = NULL;
		$this->indexed_doc_query = NULL;
	}
}