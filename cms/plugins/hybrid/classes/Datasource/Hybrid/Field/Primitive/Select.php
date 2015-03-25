<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Select extends DataSource_Hybrid_Field_Primitive {
	
	protected $_is_indexable = FALSE;
	
	protected $_loaded_options = NULL;
	protected $_options = array();
	
	protected $_props = array(
		'custom_option' => FALSE,
		'empty_value' => TRUE,
	);
	
	public function booleans()
	{
		return array('custom_option', 'empty_value');
	}
	
	public function set(array $data)
	{
		if ($this->loaded())
		{
			$current_options = $this->load_from_db();
			if (empty($data['options']) AND ! empty($current_options))
			{
				$data['options'] = array();
			}
		}

		return parent::set($data);
	}
	
	public function set_new_options(array $options)
	{
		foreach ($options as $option)
		{
			$this->add_option($option);
		}

		return $this;
	}

	/**
	 * 
	 * @param array $options
	 * @return \DataSource_Hybrid_Field_Primitive_Select
	 */
	public function set_options(array $options)
	{
		$current_options = $this->load_from_db();
		$remove = array_diff(array_keys($current_options), array_keys($options));
		$this->remove_options($remove);

		return $this;
	}

	/**
	 * 
	 * @param string $value
	 * @return integer
	 */
	public function add_option($value)
	{
		if ($this->id === NULL)
		{
			return NULL;
		}
			
		$value = trim($value);

		if (empty($value))
		{
			return NULL;
		}

		$data = array(
			'field_id' => $this->id,
			'value' => $value,
			'position' => DB::expr('IFNULL(:position, 0) + 10', array(
				':position' => DB::select(array(DB::expr('MAX(position)'), 'position'))
					->from(array('dshfield_enums', 'df'))
					->where('field_id', '=', $this->id)
			))
		);

		list($id, $num_rows) = DB::insert('dshfield_enums')
			->columns(array_keys($data))
			->values($data)
			->execute();

		return $id;
	}
	
	public function remove_options(array $ids)
	{
		if (empty($ids))
		{
			return NULL;
		}

		return (bool) DB::delete('dshfield_enums')
			->where('field_id', '=', $this->id)
			->where('id', 'IN', $ids)
			->execute();
	}
	
	public function load_from_db()
	{
		if ($this->_loaded_options === NULL)
		{
			$this->_loaded_options = DB::select('id', 'value')
				->from('dshfield_enums')
				->where('field_id', '=', $this->id)
				->order_by('position')
				->execute()
				->as_array('id', 'value');
		}

		return $this->_loaded_options;
	}

	/**
	 * 
	 * @return array
	 */
	public function get_options()
	{
		$this->_options = $this->load_from_db();

		if ($this->empty_value === TRUE)
		{
			$this->_options = array('--- Not set ---') + $this->_options;
		}

		return $this->_options;
	}
	
	/**
	 * 
	 * @param array $data
	 * @return DataSource_Hybrid_Field
	 */
	public function onReadDocumentValue(array $data, DataSource_Hybrid_Document $document)
	{
		if ($this->custom_option === TRUE AND isset($data[$this->name . '_custom']) AND ! empty($data[$this->name . '_custom']))
		{
			$option = $data[$this->name . '_custom'];

			$option_id = $this->add_option($option);

			if ($option_id !== NULL)
			{
				$document->set($this->name, $option_id);
			}

			return $this;
		}

		return parent::onReadDocumentValue($data, $document);
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$value = (int) $new->get($this->name);

		if (array_key_exists($value, $this->_options) OR ( $this->custom_option === TRUE AND ! empty($value)))
		{
			$new->set($this->name, $value);
		}
		else if ($value == 0 AND $this->empty_value === TRUE)
		{
			$new->set($this->name, '');
		}
		else
		{
			$new->set($this->name, $old->get($this->name));
		}
	}
	
	public function onValidateDocument(Validation $validation, DataSource_Hybrid_Document $doc)
	{
		$options = $this->get_options();

		if ($this->empty_value === TRUE)
		{
			$options = array(0) + $options;
		}

		$validation->rule($this->name, 'array_key_exists', array(':value', $options));

		return parent::onValidateDocument($validation, $doc);
	}

	public function get_type()
	{
		return 'VARCHAR (255) NOT NULL';
	}
	
	public function get_query_props(Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		$query->select(array($this->table_column_key(), $this->id . '::original'));
		$query->select(array($this->get_subquery(), $this->id));
	}
	
	protected function get_subquery()
	{
		return DB::select('value')
			->from('dshfield_enums')
			->where('field_id', '=', $this->id)
			->where('id', '=', DB::expr(Database::instance()->quote_column($this->name)));
	}

	public function filter_condition(Database_Query $query, $condition, $value, array $params = NULL)
	{
		$query
			->having($this->id, $condition, $value);
	}
}