<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Agent {

	const COND_EQ = 0;
	const COND_BTW = 1;
	const COND_GT = 2;
	const COND_LT = 3;
	const COND_GTEQ = 4;
	const COND_LTEQ = 5;
	const COND_CONTAINS = 6;
	const COND_LIKE = 7;

	const VALUE_CTX = 10;
	const VALUE_PLAIN = 20;
	
	/**
	 *
	 * @var integer
	 */
	public $ds_id;
	
	/**
	 *
	 * @var string
	 */
	public $ds_name;
	
	/**
	 *
	 * @var array
	 */
	public $ds_fields = NULL;
	
	/**
	 *
	 * @var array
	 */
	public $ds_field_names = NULL;
	
	/**
	 *
	 * @var array
	 */
	public $sys_fields = NULL;
	
	public function __construct($ds_id, $ds_name) 
	{
		$this->ds_id = $ds_id;
		$this->ds_name = $ds_name;
	}

	/**
	 * 
	 * @return array
	 */
	public function get_fields()
	{
		if($this->ds_fields !== NULL)
		{
			return $this->ds_fields;
		}
		
		$this->ds_fields = $this->ds_field_names = array();
		
		$query = DB::select('id', 'ds_id', 'name', 'family', 'type', 'header', 'from_ds')
			->from('dshfields')
			->where('ds_id', '=', $this->ds_id)
			->execute();
		
		foreach ($query as $row)
		{
			$name = str_replace( DataSource_Hybrid_Field::PREFFIX, '', $row['name']);
			$id = $row['id'];

			$this->ds_fields[$id] = array(
				'ds_id' => $row['ds_id'],
				'family' => $row['family'], 
				'name' => $name,
				'type' => $row['type'],
				'header' => $row['header']
			);
			
			if($row['family'] === DataSource_Hybrid_Field::FAMILY_SOURCE)
			{
				$this->ds_fields[$id]['from_ds'] = $row['from_ds'];
			}
			
			$this->ds_field_names[$id] = $name;
		}
		
		return $this->ds_fields;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function get_system_fields()
	{
		if($this->sys_fields === NULL)
		{
			$this->sys_fields = array(
				'id' => array(
					'ds_id' => $this->ds_id, 
					'type' => 'primitive_integer', 
					'name' => 'ds.id', 
					'sys' => TRUE
				),
				'header' => array(
					'ds_id' => $this->ds_id, 
					'type' => 'primitive_string', 
					'name' => 'd.header', 
					'sys' => TRUE
				),
				'created_on' => array(
					'ds_id' => $this->ds_id, 
					'type' => 'primitive_datetime', 
					'name' => 'd.created_on', 
					'sys' => TRUE
				)
			);
		}

		return $this->sys_fields;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function get_field_names() 
	{
		if($this->ds_fields === NULL)
		{
			$this->get_fields();
		}

		return $this->ds_field_names;
	}
	
	/**
	 * 
	 * @param array $fields
	 * @param array $order
	 * @param array $filter
	 * @return Database_Query_Builder_Select
	 */
	public function get_query_props($fields, $fetched_objects = array(), $order = array(), $filter = array())
	{
		$result = DB::select('d.id', 'd.ds_id', 'd.header', 'd.published', 'd.created_on')
			->from(array('dshybrid_' . $this->ds_id,  'ds'))
			->join(array('dshybrid', 'd'))
				->on('d.id', '=', 'ds.id');
		
		$ds_fields = $this->get_fields();
		$sys_fields = $this->get_system_fields();
		
		$t = array($this->ds_id => TRUE);

		for($i = 0, $l = count($fields); $i < $l; $i++) 
		{
			$fid = $fields[$i];
			
			if(!isset($ds_fields[$fid])) continue;
			
			$field = $ds_fields[$fid];
			
			if(!isset($t[$field['ds_id']])) 
			{
				$result->join(array('dshybrid_'.$field['ds_id'], 'd' . $i))
					->on('d' . $i, '=', ds.id);
	
				$t[$field['ds_id']] = TRUE;
			}

			$result->select(array(DataSource_Hybrid_Field::PREFFIX . $field['name'], $fid));
			
//			if($field['type'] == DataSource_Hybrid_Field::TYPE_DATASOURCE) 
//			{
//				
//	
//				$dss[$fid] = TRUE;
//			}
//			// TODO протестировать
//			elseif($field['type'] == DataSource_Hybrid_Field::TYPE_DOCUMENT AND isset($fetched_objects[$fid])) 
//			{
//				$result->join(array('ds' . $field['ds_type'], 'dss' . $fid), 'left')
//					->on(DataSource_Hybrid_Field::PREFFIX . $field['name'], '=', 'dss' . $fid . '.id')
//					->on('dss' . $fid . '.published', '=', DB::expr( 1 ))
//					->select(array('dss'.$fid.'.header', $fid . 'header'));
//				
//				$dds[$fid] = TRUE;
//			}
//			elseif($field['type'] == DataSource_Hybrid_Field::TYPE_USER) 
//			{
//				$result->join('users', 'left')
//					->on(DataSource_Hybrid_Field::PREFFIX . $field['name'], '=', 'users' . '.id')
//					->select(array('users.username', $fid))
//					->select(array('users.id', 'user_id'));
//			}

			unset($field);
		}
		
		
		$this->_fetch_orders($order, $t, $result);
		$this->_fetch_filters($filter, $t, $result);

		return $result;
	}
	
	protected function _fetch_orders($orders, &$t, & $result)
	{
		$j = 0;
		$ds_fields = $this->get_fields();
		$sys_fields = $this->get_system_fields();
		foreach ($orders as $pos => $data)
		{
			$field = NULL;
			$fid = key($data);
			$dir = $data[key($data)];

			if(isset($ds_fields[$fid])) 
			{
				$field = $ds_fields[$fid];
			}
			else if (isset($sys_fields[$fid]))
			{
				$field = $sys_fields[$fid];
			}

			if( $field === NULL ) continue;

			if(!isset($t[$field['ds_id']])) 
			{
				$result->join(array('dshybrid_'. $field['ds_id'], 'dorder' . $j))
					->on('dorder' . $j . '.id', '=', 'ds.id');

				$t[$field['ds_id']] = TRUE;
			}

//			if($field['type'] == DataSource_Hybrid_Field::TYPE_DATASOURCE) 
//			{
//				if(!isset($dss[$fId]))
//				{
//					$result
//						->join(array('datasources', 'dss' . $fid), 'left')
//						->on(DataSource_Hybrid_Field::PREFFIX . $field['name'], '=', 'dss' . $fid . '.ds_id');
//				}
//
//				$result->order_by('dss' . $fid . '.docs', $dir);
//			} 
//			elseif($field['type'] == DataSource_Hybrid_Field::TYPE_DOCUMENT) 
//			{
//				if(!isset($dds[$fid])) 
//				{
//					$result
//						->select(array('dss' . $fid . '.header',  $fid . 'header'))
//						->join(array('ds' . $field['ds_type'], 'dss' . $fid), 'left')
//						->on(DataSource_Hybrid_Field::PREFFIX . $field['name'], '=', 'dss' . $fid . '.id')
//						->on('dss' . $fid . '.published', '=', DB::expr( 1 ))
//						->order_by('dss' . $fid . '.header', $dir);
//				} 
//				else
//				{
//					$result->order_by($fid . 'header', $dir);
//				}
//			}
//			else
//			{
				$field_name = isset($field['sys']) ? '': DataSource_Hybrid_Field::PREFFIX;
				$result->order_by($field_name . $field['name'], $dir);
//			}

			unset($field);

			$j++;
		}
	}
	
	protected function _fetch_filters($filters, & $t, & $result)
	{
		if(empty($filters)) return;

		$field_names = $this->get_field_names();
		$ds_fields = $this->get_fields();
		$sys_fields = $this->get_system_fields();

		foreach ($filters as $pos => $data)
		{
			$condition = $data['condition'];
			$type = $data['type'];
			$invert = !empty($data['invert']);
			$field = $data['field'];

			if($type == self::VALUE_PLAIN)
			{
				$value = $data['value'];
			}
			else
			{
				$value = Context::instance()->get($data['value']);
			}
			
			if(empty($value)) continue;

			$field_id = strpos($field, '$') == 1 
				? Context::instance()->get(substr($field, 1))
				: $field;

			if(isset($sys_fields[$field_id]))
			{
				$field = $sys_fields[$field_id];
			}
			else if(isset($ds_fields[$field_id]))
			{
				$field = $ds_fields[$field_id];
			}
			else if(isset($field_names[$field_id]))
			{
				$field = $ds_fields[$field_names[$field_id]];
			}
			else
				$field = NULL;

			if(!is_array( $field )) continue;
			
			if( !isset( $t[$field['ds_id']] ) ) 
			{
				$result->join('dshybrid_' . $field['ds_id'], 'dfilter' . $pos)
					->on('dfilter' . $pos . '.id', '=', 'ds.id');
				
				$t[$field['ds_id']] = TRUE;
			}
	
			$field_name = isset($sys_fields[$field_id]) 
					? $field['name']
					: DataSource_Hybrid_Field::PREFFIX . $field['name'];
	
			$in = FALSE;
			switch($condition) 
			{
				case self::COND_EQ:
					$value = explode(',', $value);
					
					if($value[0] == '*') 
						break;
					elseif( count( $value ) > 1)
						$in = TRUE;
					else
						$value = $value[0];
					break;
				case self::COND_CONTAINS:
					$value = explode(',', $value);
					$in = TRUE;
					break;
				case self::COND_BTW:
					$value = explode(',', $value, 2);
					if(count($value) != 2) break;
					break;
				default:
					$value = $value;
			}
			$in = $in === TRUE
				? 'IN' 
				: '=';
			
			if(is_array($value))
			{
				foreach($value as $i => $v)
				{
					if( preg_match('/now()|curdate()|curtime()|interval/i', $v ))
					{
						$value[$i] = DB::expr($v);
					}
				}
			}
			else
				if( preg_match('/now()|curdate()|curtime()|interval/i', $value ))
				{
					$value = DB::expr($value);
				}
	
			$conditions = array($in, 'BETWEEN', '>', '<', '>=', '<=', 'IN', 'LIKE');
			$condition = Arr::get($conditions, $condition, '=');
			
			if($invert === TRUE)
			{
				switch ($condition)
				{
					case '>':
						$condition = '<=';
						break;
					case '<':
						$condition = '>=';
						break;
					case '=':
						$condition = '!=';
						break;
					case 'IN':
					case 'LIKE':
					case 'BETWEEN':
						$condition = 'NOT ' . $condition;
						break;
					case '>=':
						$condition = '<';
						break;
					case '<=':
						$condition = '>';
						break;
				}
			}

			$type = NULL;
			$fid = NULL;
			foreach($ds_fields as $id => $f) 
			{
				if($f['name'] == $field['name']) 
				{
					$type = $f['type'];
					$fid = $id;
				}
			}
	
//			switch($type) 
//			{
//				case DataSource_Hybrid_Field::TYPE_TAGS:
//					$result
//						->join(array(DataSource_Hybrid_Field_Tags::TABLE_NAME, $fid.'_f_ht'), 'inner')
//						->on($fid.'_f_ht.field_id', '=', DB::expr( $fid ))
//						->on($fid.'_f_ht.doc_id', '=', 'd.id')
//						->join(array(Model_Tag::TABLE_NAME, $fid.'_f_tags'), 'inner')
//						->on($fid.'_f_tags.id', '=', $fid.'_f_ht.tag_id')
//						->where($fid.'_f_tags.name', $condition, $value);
//					break;
//				default:
					$result->where($field_name, $condition, $value);
//			}
			
		}
	}

	/**
	 *
	 * @var array 
	 */
	protected static $_instance = array();

	/**
	 * 
	 * @param string|integer $ds_id
	 * @param string $type
	 * @param boolean $only_sub
	 * @return DataSource_Hybrid_Agent
	 */
	public static function instance($ds_id, $type = NULL)
	{
		if(isset(self::$_instance[$ds_id]))
		{
			return self::$_instance[$ds_id];
		}
		
		$result = DB::select('id', 'name')
			->from('datasources')
			->where('type', '=', 'hybrid')
			->where('id', '=', (int) $ds_id)
			->execute();
		
		if($result->count() > 0)
		{
			$current = $result->current();
			$ds_id = $current['id'];
			$ds_name = $current['name'];
			
			self::$_instance[$ds_id] = new DataSource_Hybrid_Agent($ds_id, $ds_name);
		}
		else
		{
			self::$_instance[$ds_id] = NULL;
		}
		
		return self::$_instance[$ds_id];
	}
}