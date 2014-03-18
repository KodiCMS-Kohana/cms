<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Datasource
 */

class DataSource_Hybrid_Field_Factory {

	/**
	 * 
	 * @param DataSource_Hybrid_Record $record
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function create_field( 
			DataSource_Hybrid_Record $record, DataSource_Hybrid_Field $field) 
	{
		$field->name = self::get_full_key($field->name);
		
		$field->set_ds($record->ds_id);
		$field->get_type();

		if( ! self::field_not_exists($field->name, $record->ds_id) )
		{
			return FALSE;
		}

		if($field->create()) 
		{
			self::alter_table_add_field($field);

			$record->fields[$field->name] = $field;
			
			return $field->id;
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Field $old
	 * @param DataSource_Hybrid_Field $new
	 * 
	 * @return DataSource_Hybrid_Field
	 */
	public static function update_field($old, $new) 
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

		return $new;
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Record $record
	 * @param array $keys
	 */
	public static function remove_fields($record, $keys) 
	{
		if($keys === NULL)
		{
			return;
		}

		if(!is_array( $keys ))
		{
			$keys = array($keys);
		}
		
		$fields = $record->fields();

		foreach($keys as $key)
		{
			if(
				isset($fields[$key]) 
			AND
				$fields[$key]->ds_id == $record->ds_id
			) 
			{
				$fields[$key]->remove();
				
				self::alter_table_drop_field($fields[$key]);
			}
		}
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return null|DataSource_Hybrid_Field
	 */
	public static function get_field($id) 
	{
		$id = (int) $id;

		$result = self::get_fields(array($id));
		
		if(empty($result))
		{
			$result[0] = NULL;
		}
			
		return $result[0];
	}
	
	/**
	 * 
	 * @param array $ids
	 * @return array
	 */
	public static function get_fields( array $ids = NULL ) 
	{
		$result = array();
		
		if( empty($ids) )
		{
			return $result;
		}
		
		$query = DB::select()
			->from('dshfields')
			->where('id', 'in', $ids)
			->order_by('position', 'asc')
			->execute();

		if($query)
		{
			foreach ($query as $row)
			{
				$result[] = self::_get_field_from_array($row);
			}
		}

		return $result;
	}
	
	/**
	 * 
	 * @param integer $ds_id
	 * @return array
	 */
	public static function get_related_fields($ds_id, $type = NULL) 
	{
		static $f;

		$ds_id = (int) $ds_id;
		
		if(isset($f[$ds_id]))
		{
			return $f[$ds_id];
		}
		
		$result = array();

		$query = DB::select()
			->from('dshfields')
			->where('ds_id', '=', $ds_id)
			->order_by('position');
		
		if(is_string($type))
		{
			$query->where('type', '=', $type);
		}
		else if(is_array($type))
		{
			$query->where('type', 'in', $type);
		}
		
		$query = $query->execute();

		foreach ($query as $row)
		{
			$result[$row['id']] = self::_get_field_from_array($row);
		}
		
		$f[$ds_id] = $result;

		return $result;
	}
	
	/**
	 * 
	 * @param array $r
	 * @return null|\DataSource_Hybrid_Field
	 * @throws Kohana_Exception
	 */
	protected static function _get_field_from_array($r) 
	{
		$result = NULL;

		if(empty($r))
		{
			return $result;
		}
			
		$class_name = 'DataSource_Hybrid_Field_' . $r['type'];
		
		if( ! class_exists( $class_name ))
		{
			throw new Kohana_Exception('Class :class_name not exists', array(
				':class_name' => $class_name));
		}
		
		if(isset($r['props']))
		{
			$props = unserialize($r['props']);
			unset($r['props']);
			
			if( is_array( $props))
			{
				$r = array_merge($r, $props);
			}
		}


		$result = DataSource_Hybrid_Field::factory($r['type'], $r);

		$result->set_id( $r['id'] );
		$result->set_ds( $r['ds_id'] );

		return $result;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return string
	 */
	public static function get_full_key($key)
	{
		$key = str_replace(DataSource_Hybrid_Field::PREFFIX, '', $key);
		$key = URL::title($key, '_');
		$key = strtolower($key);
		
		if(strlen($key) > 16)
		{
			$key = substr($key, 0, 16);
		}
		
		if(empty($key))
		{
			return NULL;
		}
		
		return DataSource_Hybrid_Field::PREFFIX . $key;
	}
	
	/**
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
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_add_field($field) 
	{
		$params = array(
			':table' => DB::expr($field->ds_table),
			':key' => DB::expr($field->name),
			':type' => DB::expr($field->get_type()),
			':default' => DB::expr('')
		);
		
		if(!empty($field->default))
		{
			$params[':default'] = DB::expr('DEFAULT "' .  $field->default . '"');
		}
		
		return (bool) DB::query(NULL, 
				'ALTER TABLE `:table`  ADD `:key` :type :default'
			)
			->parameters($params)
			->execute();
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_drop_field($field)
	{
		$params = array(
			':table' => DB::expr($field->ds_table),
			':key' => DB::expr($field->name)
		);

		return (bool) DB::query(NULL, 
				'ALTER TABLE `:table` DROP `:key`'
			)
			->parameters($params)
			->execute();
	}
	
	/**
	 * 
	 * @param DataSource_Hybrid_Field $field
	 * @return boolean
	 */
	public static function alter_table_update_field($old, $field)
	{
		$params = array(
			':table' => DB::expr($field->ds_table),
			':old_key' => DB::expr($old->name),
			':new_key' => DB::expr($field->name),
			':type' => DB::expr($field->get_type()),
			':default' => DB::expr('')
		);
		
		if(!empty($field->default))
		{
			$params[':default'] = DB::expr('DEFAULT "' .  $field->default . '"');
		}

		return (bool) DB::query(NULL, 
				'ALTER TABLE `:table` CHANGE `:old_key` `:new_key` :type :default'
			)
			->parameters($params)
			->execute();
	}
}