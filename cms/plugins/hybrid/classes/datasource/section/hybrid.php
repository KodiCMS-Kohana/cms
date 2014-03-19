<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Section_Hybrid extends Datasource_Section {
	
	/**
	 *
	 * @var string
	 */
	protected $_ds_table = 'dshybrid';
	
	/**
	 *
	 * @var string
	 */
	protected $_type = 'hybrid';
	
	/**
	 *
	 * @var array 
	 */
	public $search_index_fields = array();
	
	/**
	 *
	 * @var integer 
	 */
	public $search_intro_field = NULL;
	
	/**
	 *
	 * @var string 
	 */
	public $template = NULL;

	/**
	 *
	 * @var string 
	 */
	public $read_sql = NULL;

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
	
	public function create( array $values )
	{
		$id = parent::create($values);
		
		$dsf = new DataSource_Hybrid_Factory();
		$dsf->create($this);
		
		return $id;
	}
	
	public function valid(array $array)
	{
		$array = Validation::factory($array)
			->rules('name', array(
				array('not_empty')
			))
			->label('name', __('Header') );

		if( ! $array->check())
		{
			throw new Validation_Exception($array);
		}
	}
	
	/**
	 * 
	 * @return array
	 */
	public function record_fields_array( )
	{
		$record = $this->record();
		
		$fields = array();

		foreach($record->fields() as $field)
		{
			$fields[$field->id] = $field->header;
		}
		
		return $fields;
	}
	
	public function save(array $values = NULL)
	{
		if( ! $this->loaded())
		{
			return FALSE;
		}
		
		$this->doc_order = Arr::get($values, 'doc_order', array());
		
		$this->template = empty($values['template']) ? NULL : $values['template'];
		
		$this->search_intro_field = empty($values['search_intro_field']) ? NULL : $values['search_intro_field'];
		unset($values['search_intro_field']);
		
		$this->search_index_fields = (array) Arr::get($values, 'search_index_fields', array());
		unset($values['search_index_fields']);

		$status = parent::save($values);
		
		if(is_array($values))
		{
			$headline_fields = Arr::get($values, 'in_headline', array());
			foreach($this->record()->fields() as $f)
			{
				$value = Arr::get($headline_fields, $f->id, 0);

				$field = DataSource_Hybrid_Field_Factory::get_field($f->id);
				$old_field = clone($field);

				$field->set(array('in_headline' => $value));

				DataSource_Hybrid_Field_Factory::update_field($old_field, $field);
			}
		}
		
		return $status;
	}

	/**
	 * 
	 * @return \DataSource_Hybrid_Section
	 */
	public function remove() 
	{
		$ids = DB::select('id')
			->from('dshybrid')
			->where('ds_id', '=', $this->id())
			->execute()
			->as_array(NULL, 'id');
		
		$this->remove_own_documents($ids);

		$record = $this->record();
		$record->destroy();
		
		$dsf = new DataSource_Hybrid_Factory();
		$dsf->remove($this->id());
		
		return parent::remove();
	}
	
	/**
	 * 
	 * @param Datasource_Document $doc
	 * @return Datasource_Document
	 */
	public function create_document($doc) 
	{
		$id = $this->create_empty_document($doc->header);
		$doc->id = $id;

		$record = $this->record();
		$record->initialize_document($doc);
		$query = $record->get_sql($doc);

		$success = TRUE;
	
		foreach($query as $q)
		{
			$_query = DB::query(Database::UPDATE, $q)->execute();
		}

		if($success) 
		{
			$this->update_size();
			$this->add_to_index(array($id));
		} 
		else 
		{
			$record->destroy_document($doc);
			$this->remove_empty_documents(array($doc->id));
			$doc->id = 0;
		}
		
		$this->clear_cache();
		
		return $doc;
	}
	
	/**
	 * 
	 * @param Datasource_Document $doc
	 * @return boolean
	 */
	public function update_document($doc) 
	{
		$old = $this->get_document($doc->id);
	
		if($old !== NULL AND !$old->id)
		{
			return FALSE;
		}

		$record = $this->record();
		$record->document_changed($old, $doc);
		$query = $record->get_sql($doc, TRUE);

		$result = TRUE;
		foreach($query as $q)
		{
			$result = DB::query(NULL, $q)->execute() AND $result;
		}

		if($old->published != $doc->published) 
		{
			if($doc->published)
			{
				$this->add_to_index(array($old->id));
			}
			else
			{
				$this->remove_from_index(array($old->id));
			}
		} 
		elseif($old->published)
		{
			$this->update_index(array($old->id));
		}
		
		$this->clear_cache();

		return $result;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return \DataSource_Hybrid_Document
	 */
	public function get_document($id)
	{
		$doc = NULL;

		if($id > 0) 
		{
			$doc = new DataSource_Hybrid_Document($this->record());
			
			if(!$this->read_sql) 
			{
				$record = $this->record();
				
				$query = DB::select(array('dshybrid.id', 'id'))
					->select('ds_id', 'published', 'header')
					->select_array( array_keys( $record->fields() ))
					->from('dshybrid')
					->where('dshybrid.id', '=', $id)
					->from("dshybrid_" . $this->id())
					->where("dshybrid_" . $this->id() . ".id", '=', DB::expr('`dshybrid`.`id`'))
					->limit(1);


				$this->read_sql = (string) $query;
			}
			
			$result = DB::query( Database::SELECT, $this->read_sql )
				->execute()
				->current();

			if($result)
			{
				$doc->load_from_db($result);
			}
		}

		return $doc;
	}
	
	public function get_indexable_docs($id = NULL) 
	{
		$result = array();
		$fields = $this->search_index_fields;
		
		if(!empty($this->search_intro_field))
		{
			$fields[] = $this->search_intro_field;
		}

		$agent = DataSource_Hybrid_Agent::instance($this->id(), $this->id());
		
		$query = $agent->get_query_props($this->search_index_fields);
		
		if(is_array($id) AND !empty($id))
		{
			$query->where('d.id', 'in', $id);
		}
		else if(!empty($id))
		{
			$query->where('d.id', '=', (int) $id);
		}
		
		foreach ($query->execute() as $row)
		{
			$doc_id = $row['id'];
			$result[$doc_id] = array(
				'id' => $row['id'],
				'intro' => Arr::get($row, $this->search_intro_field),
				'header' => $row['header']
			);
			
			unset(
					$row['id'], 
					$row['ds_id'], 
					$row['published'], 
					$row['created_on'],
					$row['updated_on'], 
					$row['header']
			);
			$content = '';
			foreach ($row as $key => $value)
			{
				$content .= ' ' . (string)$value;
			}
			
			$result[$doc_id]['content'] = $content;
		}
	
		return $result;
	}
	
	/**
	 * 
	 * @return \DataSource_Hybrid_Document
	 */
	public function get_empty_document() 
	{
		$doc = new DataSource_Hybrid_Document($this->record());
		
		return $doc;
	}
	
	/**
	 * 
	 * @param string $header
	 * @return null|integer
	 */
	public function create_empty_document($header) 
	{
		$data = array(
			'ds_id' => $this->id(),
			'header' => $header,
			'created_on' => date('Y-m-d H:i:s'),
		);
		
		$query = DB::insert('dshybrid')
			->columns(array_keys($data))
			->values(array_values($data))
			->execute();

		$id = $query[0];

		$success = TRUE;

		if( $id )
		{
			$query = DB::insert("dshybrid_" . $this->id())
				->columns(array('id'))
				->values(array($id))
				->execute();
			
			return $id;
		}
		
		$this->remove_empty_documents(array($id));

		return NULL;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return \DataSource_Hybrid_Section
	 */
	public function remove_empty_documents($ids) 
	{
		if(empty($ids))
		{
			return $this;
		}

		DB::delete("dshybrid")
			->where('id', 'in', $ids)
			->execute();
		
		DB::delete("dshybrid_" . $this->id())
			->where('id', 'in', $ids)
			->execute();

		$this->remove_from_index($ids);
		
		$this->clear_cache();
		
		return $this;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return \DataSource_Hybrid_Section
	 */
	public function delete($ids) 
	{
		$dsf = new DataSource_Hybrid_Factory();
		$dsf->remove_documents($ids);
		
		return $this;
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return \DataSource_Hybrid_Section
	 */
	public function remove_own_documents($ids) 
	{
		$this->remove_empty_documents($ids);
		$this->update_size();
		
		return $this;
	}
	
	/**
	 * 
	 * @param \DataSource_Document $doc
	 * @param string $field
	 * @param integer $id
	 * @return boolean
	 */
	function set_field($doc, $field, $id) 
	{
		$db_field = DB::select('id', 'ds_id', 'name', 'family', 'isown')
			->from('dshfields')
			->where('id', '=', $field)
			->where('from_ds', '=', $this->id())
			->limit(1)
			->execute()
			->current();
		
		if($db_field === NULL)
		{
			return FALSE;
		}

		$ds_id = (int) $db_field['ds_id'];
		$field_name = $db_field['name'];
		$family = $r['family'];
		
		$doc_filed = DB::select($field_name)
			->from('dshybrid_' . $ds_id)
			->where('id', '=', $doc)
			->limit(1)
			->execute()
			->get($field_name);

		if($doc_filed === NULL)
		{
			return FALSE;
		}

		$oldvalue = $doc_filed;
		$newvalue = ($oldvalue ? $oldvalue . ',' : '') . $id;
		if(UTF8::strlen($newvalue) > 255)
		{
			return FALSE;
		}
		
		DB::update('dshybrid_' . $ds_id)
			->set(array(
				$field_name => $newvalue
			))
			->where('id', '=', $doc)
			->limit(1)
			->execute();
		
		$this->clear_cache();

		return TRUE;
	}
	
	/**
	 * @param integer $doc_id
	 * @return \DataSource_Document
	 * @throws Kohana_Exception
	 */
	public function get_doc($doc_id) 
	{
		static $ds, $doc;
		$result = NULL;
		
		$doc_id = (int) $doc_id;

		if(isset($doc[$doc_id]))
		{
			$result = $doc[$doc_id];
		}
		else 
		{
			$ds_id = DB::select('ds_id')
				->from('dshybrid')
				->where('id', '=', $doc_id)
				->execute()
				->get('ds_id');
	
			if(!isset($ds[$ds_id])) 
			{
				$ds[$ds_id] = Datasource_Data_Manager::load($ds_id);

				if($ds[$ds_id] === NULL)
				{
					throw new Kohana_Exception('NULL object');
				}
			}

			$doc[$doc_id] = $ds[$ds_id]->get_document($doc_id);
			$result = $doc[$doc_id];
		}

		return $result;
	}
	
	/**
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
	
	public function clear_cache( )
	{
		Datasource_Data_Manager::clear_cache( $this->id(), DataSource_Hybrid_Factory::$widget_types);
	}

	protected function _serialize()
	{
		$vars = parent::_serialize();

		unset(
			$vars['_agent'], 
			$vars['_record'], 
			$vars['read_sql'], 
			$vars['indexed_doc_query']
		);
		
		return $vars;
	}
	
	protected function _initialize()
	{
		parent::_initialize();
		
		$this->_record = NULL;
		$this->_agent = NULL;
		$this->read_sql = NULL;
		$this->indexed_doc_query = NULL;
	}
}