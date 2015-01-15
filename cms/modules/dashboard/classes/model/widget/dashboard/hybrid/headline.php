<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Dashboard
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Dashboard_Hybrid_Headline extends Model_Widget_Decorator_Dashboard_Pagination {	
	
	/**
	 *
	 * @var boolean
	 */
	protected $_multiple = TRUE;
	
	/**
	 *
	 * @var boolean 
	 */
	protected $_update_settings_page = TRUE;
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fields = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_filter = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_order = array();
	
	/**
	 *
	 * @var boolean 
	 */
	public $only_published = TRUE;
	
	/**
	 *
	 * @var array 
	 */
	protected $arrays = array();
	
	/**
	 *
	 * @var array 
	 */
	public $docs = NULL;
	
	protected $_data = array(
		'height' => 250
	);
	
	protected $_size = array(
		'x' => 4,
		'y' => 3,
		'max_size' => array(8, 8),
		'min_size' => array(3, 2)
	);


	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->doc_fields = $this->doc_fetched_widgets = array();
		$this->doc_filter = array();

		parent::set_values($data);
		$this->doc_order = Arr::get($data, 'doc_order', array());
		$this->only_published = (bool) Arr::get($data, 'only_published');

		return $this;
	}
	
	public function set_ds_id($ds_id)
	{
		return (int) $ds_id;
	}
	
	public function set_height($height) 
	{
		return (int) $height;
	}
	
	public function set_field($fields = array())
	{
		if (!is_array($fields))
		{
			return;
		}

		foreach ($fields as $f)
		{
			if (isset($f['id']))
			{
				$this->doc_fields[] = (int) $f['id'];
			}
		}
	}
	
	public function set_doc_filter(array $filters)
	{
		$data = array();
		foreach ($filters as $key => $rows)
		{
			foreach ($rows as $i => $row)
			{
				$data[$i][$key] = $row;
			}
		}

		return $data;
	}
	
	public function count_total()
	{
		return $this->get_total_documents();
	}
	
	public function reset()
	{
		$this->docs = NULL;
		return $this;
	}

	/**
	 * 
	 * @return array [$section, $docs, $fields, $count]
	 */
	public function fetch_data()
	{
		if (!$this->ds_id)
		{
			return array();
		}

		list($docs, $fields) = $this->get_documents();

		return array(
			'section' => Datasource_Section::load($this->ds_id),
			'docs' => $docs,
			'fields' => $fields,
			'count' => count($this->docs)
		);
	}
	
	public function get_total_documents()
	{
		$agent = DataSource_Hybrid_Agent::instance($this->ds_id);

		$query = $agent->get_query_props(array(), array(), $this->doc_filter);

		if ($this->only_published === TRUE)
		{
			$query->where('d.published', '=', 1);
		}

		return $query->select(array(DB::expr('COUNT(*)'), 'total_docs'))
			->execute()
			->get('total_docs');
	}
	
	/**
	 * 
	 * @param integer $recurse
	 * @return array
	 */
	public function get_documents( $recurse = 3 )
	{
		if ($this->docs !== NULL)
		{
			return $this->docs;
		}

		$result = array();
		$return_fields = array();

		$agent = DataSource_Hybrid_Agent::instance($this->ds_id);

		$query = $this->_get_query();

		$ds_fields = $agent->get_fields();

		$fields = array();
		foreach ($this->doc_fields as $fid)
		{
			if (isset($ds_fields[$fid]))
			{
				$fields[$fid] = $ds_fields[$fid];

				$return_fields[$ds_fields[$fid]->key] = $ds_fields[$fid];
			}
		}

		foreach ($query->execute() as $row)
		{
			$result[$row['id']] = array();

			$doc = & $result[$row['id']];

			$doc['id'] = $row['id'];
			$doc['header'] = $row['header'];
			$doc['created_on'] = $row['created_on'];
			$doc['published'] = (bool) $row['published'];
			
			foreach ($fields as $fid => $field)
			{
				$field_class = 'DataSource_Hybrid_Field_' . $field->type;
				$field_class_method = 'fetch_widget_field';
				if (class_exists($field_class) AND method_exists($field_class, $field_class_method))
				{
					$doc[$field->key] = call_user_func_array($field_class . '::' . $field_class_method, array($this, $field, $row, $fid, $recurse - 1));
					continue;
				}
			}
		}

		$this->docs = $result;

		return array($result, $return_fields);
	}

	/**
	 * 
	 * @return Database_Query_Builder
	 */
	protected function _get_query()
	{
		$agent = DataSource_Hybrid_Agent::instance($this->ds_id);

		$query = $agent->get_query_props($this->doc_fields, $this->doc_order, $this->doc_filter);

		if ($this->only_published === TRUE)
		{
			$query->where('d.published', '=', 1);
		}

		$query->limit($this->list_size);
		$query->offset($this->list_offset);

		return $query;
	}
}