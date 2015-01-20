<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Agent {

	/**
	 * Объекты агентов разделов
	 * @var array 
	 */
	protected static $_instance = array();

	/**
	 * Получение объекта агета для раздела
	 * 
	 * @param integer $ds_id
	 * @param boolean $only_sub
	 * @return DataSource_Hybrid_Agent
	 */
	public static function instance($ds_id)
	{
		$ds_id = (int) $ds_id;
		if (isset(self::$_instance[$ds_id]))
		{
			return self::$_instance[$ds_id];
		}

		$query = DB::select('id', 'name')
			->from('datasources')
			->where('type', '=', 'hybrid')
			->where('id', '=', $ds_id)
			->execute();

		if ($query->count() > 0)
		{
			$current = $query->current();
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
	const VALUE_BEHAVIOR = 30;
	const VALUE_GET = 40;
	const VALUE_POST = 50;
	
	/**
	 * Идентификатор раздела
	 * @var integer
	 */
	public $ds_id;
	
	/**
	 * Название раздела
	 * @var string
	 */
	public $ds_name;
	
	/**
	 * Массив полей раздела
	 * 
	 * @see DataSource_Hybrid_Agent::get_fields()
	 * @var array
	 */
	protected $_ds_fields = NULL;
	
	/**
	 * Массив названий полей
	 * 
	 * @see DataSource_Hybrid_Agent::get_fields()
	 * @var array
	 */
	protected $_ds_field_names = array();
	
	/**
	 * Системные поля раздела
	 * @var array
	 */
	protected $_sys_fields = NULL;

	/**
	 * 
	 * @param integer $ds_id
	 * @param string $ds_name
	 */
	public function __construct($ds_id, $ds_name) 
	{
		$this->ds_id = $ds_id;
		$this->ds_name = $ds_name;
	}
	
	/**
	 * 
	 * @param integer|string $id
	 * @param array $fields
	 * @param string $id_field
	 * @return null|array
	 */
	public function get_document($id, array $fields = NULL, $id_field = NULL)
	{
		$query = $this->get_query_props($fields);

		$hybrid_fields = $this->get_fields();

		if (Valid::numeric($id_field) AND isset($hybrid_fields[$id_field]))
		{
			$id_field = $hybrid_fields[$id_field]->name;
		}

		if ($id_field == 'id' OR empty($id_field))
		{
			$id_field = 'd.id';
		}

		$query = $query->where($id_field, '=', $id)
			->where('d.published', '=', 1)
			->group_by('d.id')
			->limit(1)
			->execute()
			->current();

		if (empty($query))
		{
			return NULL;
		}

		return $query;
	}

	/**
	 * Получение списка полей раздела.
	 * 
	 * @return array array([id] => [DataSource_Hybrid_Field])
	 */
	public function get_fields()
	{
		if ($this->_ds_fields !== NULL)
		{
			return $this->_ds_fields;
		}

		$this->_ds_fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id);

		foreach ($this->_ds_fields as $id => $field)
		{
			$this->_ds_field_names[$id] = $field->key;
		}

		return $this->_ds_fields;
	}

	/**
	 * Получение списка массива ключей полей
	 * 
	 * @return array array([id] => [key])
	 */
	public function get_field_names()
	{
		if ($this->_ds_fields === NULL)
		{
			$this->get_fields();
		}

		return $this->_ds_field_names;
	}

	/**
	 * Получение списка системных полей
	 * 
	 * @return array  array([id] => [DataSource_Hybrid_Field])
	 */
	public function get_system_fields()
	{
		if ($this->_sys_fields === NULL)
		{
			$this->_sys_fields = array(
				'id' => DataSource_Hybrid_Field_Factory::get_field_from_array(array(
					'ds_id' => $this->ds_id,
					'type' => 'primitive_integer',
					'name' => 'ds.id',
					'header' => 'ID',
					'system' => TRUE
				)),
				'header' => DataSource_Hybrid_Field_Factory::get_field_from_array(array(
					'ds_id' => $this->ds_id,
					'type' => 'primitive_string',
					'name' => 'd.header',
					'header' => __('Header'),
					'system' => TRUE
				)),
				'created_on' => DataSource_Hybrid_Field_Factory::get_field_from_array(array(
					'ds_id' => $this->ds_id,
					'type' => 'primitive_datetime',
					'name' => 'd.created_on',
					'header' => __('Date created'),
					'system' => TRUE
				)),
				'published' => DataSource_Hybrid_Field_Factory::get_field_from_array(array(
					'ds_id' => $this->ds_id,
					'type' => 'primitive_boolean',
					'name' => 'd.published',
					'header' => __('Published'),
					'system' => TRUE
				))
			);
		}

		return $this->_sys_fields;
	}
	
	/**
	 * Формирование Database_Query_Builder запроса на получение данных документов раздела.
	 * 
	 * Используется виджетами "Список ГД документов" и "Гибридный документ", а также
	 * списком документов в админ панели
	 * 
	 * В параметр {@param $fields} передается массив идентификаторов полей, которые 
	 * должны попасть в SELECT запрос, также для этих полей будет вызван {@see DataSource_Hybrid_Field::get_query_props()} 
	 *      
	 *		$filter = array(
	 *			array(
	 *				"field" =>     "slug"
	 *				"condition" => "0"
	 *				"type" =>      "20"
	 *				"value" =>     "test"
	 *			)
	 *		)
	 * 
	 * @param array $fields массив ID полей, которые попадут в select array([] => [field ID])
	 * @param array $order array( array( [id] => [ASC|DESC] ), .....    ) Парамтеры сортировки документов
	 * @param array $filter Параметры фильтрации спсика документов
	 * @return Database_Query_Builder
	 */
	public function get_query_props(array $fields = NULL, array $order = NULL, array $filter = NULL)
	{
		$query = DB::select('d.id', 'd.ds_id', 'd.header', 'd.published', 'd.created_on', 'd.meta_title', 'd.meta_keywords', 'd.meta_description')
			->from(array('dshybrid_' . $this->ds_id,  'ds'))
			->join(array('dshybrid', 'd'))
				->on('d.id', '=', 'ds.id');
		
		$ds_fields = $this->get_fields();

		$t = array($this->ds_id => TRUE);

		$select_fields = array();

		if (!empty($fields))
        {
			foreach ($fields as $i => $fid)
			{
				if (!isset($ds_fields[$fid]))
				{
					continue;
				}

				$select_fields[] = $ds_fields[$fid];
			}
		}
		else
		{
			$select_fields = $ds_fields;
		}

		foreach ($select_fields as $field)
		{
			if (!($field instanceof DataSource_Hybrid_Field))
			{
				continue;
			}

			if (!isset($t[$field->ds_id]))
			{
				$query->join(array('dshybrid_' . $field[$field->ds_id], 'd' . $i))
						->on('d' . $i, '=', ds . id);

				$t[$field[$field->ds_id]] = TRUE;
			}

			$field->get_query_props($query, $this);
		}

		if (!empty($order))
		{
			$this->_fetch_orders($order, $t, $query);
		}

		if (!empty($filter))
		{
			$this->_fetch_filters($filter, $t, $query);
		}

		return $query;
	}
	
	/**
	 * Формирование Database_Query_Builder в части сортировки документов
	 * 
	 * @param array $orders
	 * @param array $t
	 * @param Database_Query_Builder $query
	 * @return Database_Query_Builder
	 */
	protected function _fetch_orders(array $orders, &$t, $query)
	{
		$j = 0;
		$ds_fields = $this->get_fields();
		$sys_fields = $this->get_system_fields();

		foreach ($orders as $pos => $data)
		{
			$field = NULL;
			$fid = key($data);
			$dir = $data[key($data)];

			if (isset($ds_fields[$fid]))
			{
				$field = $ds_fields[$fid];
			}
			else if (isset($sys_fields[$fid]))
			{
				$field = $sys_fields[$fid];
			}

			if (!($field instanceof DataSource_Hybrid_Field))
			{
				continue;
			}

			if (!isset($t[$field->ds_id]))
			{
				$query->join(array('dshybrid_' . $field->ds_id, 'dorder' . $j))
					->on('dorder' . $j . '.id', '=', 'ds.id');

				$t[$field->ds_id] = TRUE;
			}

			$field->sorting_condition($query, $dir);

			unset($field);

			$j++;
		}

		return $query;
	}
	
	/**
	 * Формирование Database_Query_Builder в части филтрации документов
	 * 
	 * @param array $filters
	 * @param array $t
	 * @param Database_Query_Builder $query
	 * @return Database_Query_Builder
	 */
	protected function _fetch_filters(array $filters, & $t, & $query)
	{
		if (empty($filters))
		{
			return $query;
		}

		$field_names = array_flip($this->get_field_names());
		$ds_fields = $this->get_fields();
		$sys_fields = $this->get_system_fields();

		foreach ($filters as $pos => $data)
		{
			$params = array();

			$field = $data['field'];
	
			if (!empty($data['params']))
			{
				parse_str($data['params'], $params);
			}

			$condition = $data['condition'];
			$type = $data['type'];
			$invert = !empty($data['invert']);

			$value = Arr::get($data, 'value');

			if ($value !== NULL AND $type != self::VALUE_PLAIN)
			{
				$ctx = Context::instance();
				$request = Request::initial();

				switch ($type)
				{
					case self::VALUE_BEHAVIOR:
						if ($ctx->behavior_router() instanceof Behavior_Route)
						{
							$value = $ctx->behavior_router()->param($value);
							break;
						}
					case self::VALUE_GET:
						$value = $request->query($value);
						break;
					case self::VALUE_POST:
						$value = $request->post($value);
						break;
					default:
						$value = $ctx->get($value);
						break;
				}
			}

			if ($value === NULL)
			{
				continue;
			}

			$field_id = strpos($field, '$') == 1 
				? Context::instance()->get(substr($field, 1)) 
				: $field;

			if (isset($sys_fields[$field_id]))
			{
				$field = $sys_fields[$field_id];
			}
			else if (isset($ds_fields[$field_id]))
			{
				$field = $ds_fields[$field_id];
			}
			else if (isset($field_names[$field_id]))
			{
				$field = $ds_fields[$field_names[$field_id]];
			}
			else
			{
				$field = NULL;
			}

			if (!($field instanceof DataSource_Hybrid_Field))
			{
				continue;
			}

			if (!isset($t[$field->ds_id]))
			{
				$query->join('dshybrid_' . $field->ds_id, 'dfilter' . $pos)
					->on('dfilter' . $pos . '.id', '=', 'ds.id');

				$t[$field->ds_id] = TRUE;
			}

			$in = FALSE;
			switch ($condition)
			{
				case self::COND_EQ:
					$value = explode(',', $value);

					if ($value[0] == '*')
						break;
					elseif (count($value) > 1)
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
					if (count($value) != 2) break;
					break;

				default:
					$value = $value;
			}

			$in = $in === TRUE 
				? 'IN' 
				: '=';

			if (is_array($value))
			{
				foreach ($value as $i => $v)
				{
					if ($this->is_db_function($value))
					{
						$value[$i] = DB::expr($v);
					}
				}
			}
			else if ($this->is_db_function($value))
			{
				$value = DB::expr($value);
			}
			
			
			if (isset($params['db_function']) AND ! $this->is_db_function($params['db_function']))
			{
				unset($params['db_function']);
			}

			$conditions = array($in, 'BETWEEN', '>', '<', '>=', '<=', 'IN', 'LIKE');
			$condition = Arr::get($conditions, $condition, '=');

			if ($invert === TRUE)
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
			foreach ($ds_fields as $id => $f)
			{
				if ($f->key == $field->key)
				{
					$type = $f->type;
					$fid = $id;
				}
			}

			$field->filter_condition($query, $condition, $value, $params);
		}
		
		unset($field_names, $ds_fields, $sys_fields, $filters);

		return $query;
	}
	
	public function is_db_function($string)
	{
		if (!is_string($string))
		{
			return FALSE;
		}

		$functions = array(
			'ADDDATE',
			'ADDTIME',
			'CONVERT_TZ',
			'CURDATE',
			'CURRENT_DATE',
			'CURRENT_TIME',
			'CURRENT_TIMESTAMP',
			'CURTIME',
			'DATE_ADD',
			'DATE_FORMAT',
			'DATE_SUB',
			'DATE',
			'DATEDIFF',
			'DAY',
			'DAYNAME',
			'DAYOFMONTH',
			'DAYOFWEEK',
			'DAYOFYEAR',
			'EXTRACT',
			'FROM_DAYS',
			'FROM_UNIXTIME',
			'GET_FORMAT',
			'HOUR',
			'LAST_DAY',
			'LOCALTIME',
			'LOCALTIMESTAMP',
			'LOCALTIMESTAMP',
			'MAKEDATE',
			'MAKETIME',
			'MICROSECOND',
			'MINUTE',
			'MONTH',
			'MONTHNAME',
			'NOW',
			'PERIOD_ADD',
			'PERIOD_DIFF',
			'QUARTER',
			'SEC_TO_TIME',
			'SECOND',
			'STR_TO_DATE',
			'SUBDATE',
			'SUBTIME',
			'SYSDATE',
			'TIME_FORMAT',
			'TIME_TO_SEC',
			'TIME',
			'TIMEDIFF',
			'TIMESTAMP',
			'TIMESTAMPADD',
			'TIMESTAMPDIFF',
			'TO_DAYS',
			'TO_SECONDS',
			'UNIX_TIMESTAMP',
			'UTC_DATE',
			'UTC_TIME',
			'UTC_TIMESTAMP',
			'WEEK',
			'WEEKDAY',
			'WEEKOFYEAR',
			'YEAR',
			'YEARWEEK',
		);

		return preg_match('/' . implode('|', $functions) . '/', $string);
	}
}