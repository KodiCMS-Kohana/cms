<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Factory {
	
	const INDEX_NONE = NULL;
	const INDEX_PRIMARY = 'PRIMARY KEY';
	const INDEX_UNIQUE = 'UNIQUE';
	const INDEX_INDEX = 'INDEX';
	const INDEX_FULLTEXT = 'FULLTEXT';
	
	public static function get_last_position($ds_id)
	{
		return DB::select(array(DB::expr('MAX(position)'), 'position'))
			->from('dshfields')
			->where('ds_id', '=', (int) $ds_id)
			->execute()
			->get('position', 0);
	}
	

	/**
	 * Создание нового поля в таблице полей
	 * и создание нового поля в таблице текущего раздела
	 * 
	 * @see Controller_Hybrid_Field::_add()
	 * 
	 * @param DataSource_Hybrid_Record $record
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function create_field(DataSource_Hybrid_Record $record, DataSource_Hybrid_Field $field)
	{
		if ($field->loaded())
		{
			return FALSE;
		}

		$field->name = self::get_full_key($field->name);
		
		$field->set_ds($record->ds_id());
		if ($field->create())
		{
			if (self::alter_table_add_field($field))
			{
				$record->fields[$field->name] = $field;
				$field->onCreate();
				return $field->id;
			}
		}

		return FALSE;
	}
	
	/**
	 * Обновление поля в разделе и в таблице полей
	 * 
	 * @see Controller_Hybrid_Field::_edit()
	 * 
	 * @param DataSource_Hybrid_Field $old
	 * @param DataSource_Hybrid_Field $new
	 * 
	 * @return DataSource_Hybrid_Field
	 */
	public static function update_field(DataSource_Hybrid_Field $old, DataSource_Hybrid_Field $new)
	{		
		$new->get_type();
		
		$new->name = self::get_full_key($new->name);
		
		$new->update();

		switch ($old->family) 
		{
			case DataSource_Hybrid_Field::FAMILY_PRIMITIVE:
				self::alter_table_update_field($old, $new);
				break;
		}
		
		$new->onUpdate();

		return $new;
	}
	
	/**
	 * Удаление полей по их ключу из раздела и из таблицы полей
	 * 
	 * @param DataSource_Hybrid_Record $record
	 * @param array $keys
	 */
	public static function remove_fields_by_key(DataSource_Hybrid_Record $record, array $keys)
	{		
		$fields = $record->fields();

		$exception = FALSE;

		foreach ($keys as $key)
		{
			if (
				isset($fields[$key]) 
			AND
				$fields[$key]->ds_id == $record->ds_id()
			) 
			{
				try
				{
					$fields[$key]->remove();
					self::alter_table_drop_field($fields[$key]);
				} 
				catch (DataSource_Hybrid_Exception_Field $ex) 
				{
					$exception = $ex;
					continue;
				}
			}
		}
		
		if($exception !== FALSE)
		{
			throw $exception;
		}
	}
	
	/**
	 * Удаление полей по их ID и из таблицы полей
	 * 
	 * @param array $ids
	 */
	public static function remove_fields_by_id(array $ids)
	{
		$fields = self::get_fields($ids);
		$exception = FALSE;
		
		foreach($fields as $field)
		{
			try
			{
				$field->remove();
				self::alter_table_drop_field($field);
			} 
			catch (DataSource_Hybrid_Exception_Field $ex) 
			{
				$exception = $ex;
				continue;
			}
		}
		
		if ($exception !== FALSE)
		{
			throw $exception;
		}
	}

	/**
	 * Загрузка поля по его ID
	 * 
	 * @param integer $id
	 * @return null|DataSource_Hybrid_Field
	 */
	public static function get_field($id) 
	{
		$id = (int) $id;

		$result = self::get_fields(array($id));

		if (empty($result))
		{
			return NULL;
		}

		return reset($result);
	}
	
	/**
	 * Получение ключа поля по ID
	 * 
	 * @param integer $id
	 * @return string|null
	 */
	public static function get_field_key($id) 
	{
		$field = self::get_field($id);

		return ($field instanceof DataSource_Hybrid_Field) 
			? $field->key 
			: NULL;
	}
	
	/**
	 * Загрузка массива полей по массиву идентификаторов
	 * 
	 * @param array $ids
	 * @return array
	 */
	public static function get_fields(array $ids = NULL)
	{
		$result = array();

		if (empty($ids))
		{
			return $result;
		}

		$query = DB::select()
			->from('dshfields')
			->where('id', 'in', $ids)
			->order_by('position', 'asc')
			->execute()
			->as_array('id');

		foreach ($query as $id => $row)
		{
			$result[$id] = self::get_field_from_array($row);
		}

		return $result;
	}
	
	/**
	 * Загрузка полей раздела
	 * 
	 * @staticvar $cached_fields Кеш загруженных полей разделов
	 * 
	 * @see DataSource_Hybrid_Record::load()
	 * 
	 * @param integer $ds_id Идентификатор раздела
	 * @param array $type Ключ типа поля
	 * @return array DataSource_Hybrid_Field
	 */
	public static function get_section_fields($ds_id, array $type = NULL) 
	{
		static $cached_fields;

		$ds_id = (int) $ds_id;

		if (isset($cached_fields[$ds_id]))
		{
			return $cached_fields[$ds_id];
		}

		$fields = array();

		$query = DB::select()
			->from('dshfields')
			->where('ds_id', '=', $ds_id)
			->order_by('position');

		if (!empty($type))
		{
			$query->where('type', 'in', $type);
		}

		$query = $query
			->execute()
			->as_array('id');

		foreach ($query as $id => $row)
		{
			$field = self::get_field_from_array($row);
			if ($field === NULL)
			{
				continue;
			}

			$fields[$id] = $field;
		}

		$cached_fields[$ds_id] = $fields;

		return $fields;
	}
	
	/**
	 * Преобразование массива в объект поля
	 * 
	 * @see DataSource_Hybrid_Field_Factory::get_fields()
	 * 
	 * @param array $array
	 * @return null|\DataSource_Hybrid_Field
	 * @throws Kohana_Exception
	 */
	public static function get_field_from_array(array $array = NULL)
	{
		if (empty($array) OR ! isset($array['type']))
		{
			return NULL;
		}

		$class_name = 'DataSource_Hybrid_Field_' . $array['type'];

		if (!class_exists($class_name))
		{
			return NULL;
		}

		if (isset($array['props']))
		{
			$props = Kohana::unserialize($array['props']);
			unset($array['props']);

			if (is_array($props))
			{
				$array = Arr::merge($array, $props);
			}
		}

		$result = DataSource_Hybrid_Field::factory($array['type'], $array);
		$result->set_id(Arr::get($array, 'id'));
		$result->set_ds(Arr::get($array, 'ds_id'));

		return $result;
	}
	
	/**
	 * Генерация ключа для поля
	 * 
	 * @param string $key
	 * @return string
	 */
	public static function get_full_key($key)
	{
		$key = str_replace(DataSource_Hybrid_Field::PREFFIX, '', $key);
		$key = URL::title($key, '_');
		$key = strtolower($key);

		if (strlen($key) > 32)
		{
			$key = substr($key, 0, 32);
		}

		if (empty($key))
		{
			return NULL;
		}

		return DataSource_Hybrid_Field::PREFFIX . $key;
	}
	
	/**
	 * Проверка на уникальность ключа поля в разделе
	 * 
	 * @param string $key
	 * @param integer $ds_id
	 * @return boolean
	 */
	public static function field_not_exists($key, $ds_id)
	{
		return DB::select('id')
			->from('dshfields')
			->where('ds_id', '=', (int) $ds_id)
			->where('name', '=', $key)
			->limit(1)
			->execute()
			->get('id') === NULL;
	}

	/**
	 * Добавление поле в таблицу раздела
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_add_field(DataSource_Hybrid_Field $field) 
	{
		$db = Database::instance();
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':key' => DB::expr($db->quote_column($field->name)),
			':type' => DB::expr($field->get_type()),
			':default' => DB::expr('DEFAULT ""')
		);
		
		$default = $field->db_default_value();
		if($default === FALSE)
		{
			$params[':default'] = DB::expr('');
		}
		else if ($default !== NULL)
		{
			$params[':default'] = DB::expr('DEFAULT :value')->param(':value', $default);
		}

		return (bool) DB::query(NULL, 
			'ALTER TABLE :table ADD :key :type :default'
		)
			->parameters($params)
			->execute();
	}
	
	/**
	 * Удаление поля из таблицы раздела
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_drop_field(DataSource_Hybrid_Field $field)
	{
		$db = Database::instance();
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':key' => DB::expr($db->quote_column($field->name)),
		);

		return (bool) DB::query(NULL, 
			'ALTER TABLE :table DROP :key'
		)
			->parameters($params)
			->execute();
	}
	
	/**
	 * Обновление поля в таблице раздела
	 * 
	 * @param DataSource_Hybrid_Field $old
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_update_field(DataSource_Hybrid_Field $old, DataSource_Hybrid_Field$field)
	{
		$db = Database::instance();
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':old_key' => DB::expr($db->quote_column($old->name)),
			':new_key' => DB::expr($db->quote_column($field->name)),
			':type' => DB::expr($field->get_type()),
			':default' => DB::expr('DEFAULT ""')
		);
		
		$default = $field->db_default_value();
		if($default === FALSE)
		{
			$params[':default'] = DB::expr('');
		}
		else if ($default !== NULL)
		{
			$params[':default'] = DB::expr('DEFAULT :value')->param(':value', $default);
		}

		return (bool) DB::query(NULL, 
			'ALTER TABLE :table CHANGE :old_key :new_key :type :default'
		)
			->parameters($params)
			->execute();
	}
	
	/**
	 * Добавление индекса для поля
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @param string $type
	 * @return boolean
	 */
	public static function alter_table_field_add_index(DataSource_Hybrid_Field $field, $type = self::INDEX_INDEX) 
	{
		$db = Database::instance();
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':key' => DB::expr($db->quote_column($field->name)),
			':type' => DB::expr($type)
		);
		
		return (bool) DB::query(NULL,
			'ALTER TABLE :table ADD :type(:key)'
		)
			->parameters($params)
			->execute();
	}
	
	/**
	 * Удаление индекса из поля
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_field_drop_index(DataSource_Hybrid_Field $field) 
	{
		$db = Database::instance();
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':key' => DB::expr($db->quote_column($field->name)),
		);
		
		return (bool) DB::query(NULL,
			'ALTER TABLE :table DROP INDEX :key'
		)
			->parameters($params)
			->execute();
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function is_index(DataSource_Hybrid_Field $field)
	{
		$db = Database::instance();
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':key' => DB::expr($field->name)
		);
		
		return (bool) DB::query(NULL,
			'SHOW KEYS FROM :table WHERE `Column_name` = ":key"'
		)
			->parameters($params)
			->execute();
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function is_column_exists(DataSource_Hybrid_Field $field)
	{
		$db = Database::instance();
		
		$params = array(
			':table' => DB::expr($db->quote_table($field->ds_table)),
			':key' => DB::expr($field->name)
		);
		
		return (bool) DB::query(NULL,
			'SHOW COLUMNS FROM :table LIKE ":key"'
		)
			->parameters($params)
			->execute();
	}
	
}