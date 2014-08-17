<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_ORM extends Kohana_ORM {
	
	/**
	 *
	 * @var array 
	 */
	protected $_form_columns = array();
	
	/**
	 * 
	 * @return array
	 */
	public function form_columns()
	{
		return array();
	}

	/**
	 * 
	 * @param string $field
	 * @param array $attributes
	 * @return string
	 */
	public function label( $field, array $attributes = NULL, $only_text = FALSE )
	{
		if($only_text === FALSE)
		{
			return Form::label( $this->object_name() . '_' . $field, Arr::get($this->labels(), $field), $attributes);
		}
		
		return Arr::get($this->labels(), $field);
	}
	
	/**
	 * 
	 * @param string $field
	 * @param array $attributes
	 * @return string
	 */
	public function field( $field, array $attributes = NULL )
	{
		$field_data = Arr::get($this->form_columns(), $field);

		if ($field_data === NULL)
		{
			$field_data = array(
				'type' => Arr::path($this->_table_columns, $field . '.data_type')
			);
		}

		$field_name = $field;
		
		if(Arr::get($field_data, 'free') !== TRUE)
		{
			$value = $this->get($field);
		}
		else
		{
			$value = NULL;
		}

		if (isset($attributes['prefix']))
		{
			$field_name = $attributes['prefix'] . "[{$field_name}]";
			unset($attributes['prefix']);
		}

		$attributes['id'] = $this->object_name() . '_' . $field;

		if (isset($attributes['choices']))
		{
			$field_data['choices'] = $attributes['choices'];
			unset($attributes['choices']);
		}

		if (isset($attributes['multiply']))
		{
			$field_data['multiply'] = TRUE;
			$field_name .= '[]';
			unset($attributes['multiply']);
		}

		if (!empty($field_data['choices']))
		{
			$choices = Callback::invoke($field_data['choices']);
		}
		
		$input = NULL;
		
		if (isset($attributes['class']) AND ! is_array($attributes['class']))
		{
			$attributes['class'] = array($attributes['class']);
		}

		if (is_callable($field_data['type']))
		{
			$input = call_user_func($field_data['type'], $this, $field, $attributes);
		}
		else if (is_array($field_data['type']))
		{
			$input = Callback::invoke_function($field_data['type'], array($this, $field, $attributes));
		}
		else
		{
			switch ($field_data['type'])
			{
				case 'textarea':
				case 'text':
					$input = Form::textarea($field_name, $value, $attributes);
					break;
				case 'select':
					$input = Form::select($field_name, $choices, $value, $attributes);
					break;
				case 'checkbox':
					$default = Arr::get($field_data, 'value', 1);
					$input = Form::label(NULL, Form::checkbox($field_name, $default, $default == $value, $attributes) . '&nbsp;' . $this->label($field, NULL, TRUE));
					break;
				case 'password':
					$input = Form::password($field_name, NULL, $attributes);
					break;
				default:
					$input = Form::input($field_name, $value, $attributes);
					break;
			}
		}

		return $input;
	}

	/**
	 * 
	 * @return array
	 */
	public function list_columns()
	{
		if (Kohana::$caching === TRUE)
		{
			$cache = Cache::instance();
			if (($result = $cache->get('table_columns_' . $this->_object_name)) !== NULL)
			{
				return $result;
			}

			$cache->set('table_columns_' . $this->_object_name, $this->_db->list_columns($this->table_name()));
		}

		// Proxy to database
		return parent::list_columns();
	}

	/**
	 * 
	 * @param array $config
	 * @return Pagination
	 */
	public function add_pager(array $config = NULL)
	{
		$config['total_items'] = $this->reset(FALSE)->count_all();
		$pager = Pagination::factory($config);

		$this
			->limit($pager->items_per_page)
			->offset($pager->offset);

		return $pager;
	}

	/**
	 * 
	 * @param string $alias
	 * @return array
	 * @throws Kohana_Exception
	 */
	public function get_related_ids($alias)
	{
		if (!isset($this->_has_many[$alias]))
		{
			throw new Kohana_Exception('Relation :alias not exists in object :object', array(
		':alias' => $alias,
		':object' => $this->object_name()
			));
		}

		if (!$this->loaded())
		{
			return array();
		}

		$table_name = $this->_has_many[$alias]['through'];
		$filed = $this->_has_many[$alias]['foreign_key'];
		$related_field = $this->_has_many[$alias]['far_key'];

		return DB::select($related_field)
			->from($table_name)
			->where($filed, '=', $this->pk())
			->execute($this->_db)
			->as_array(NULL, $related_field);
	}

	/**
	 * 
	 * @param string $alias
	 * @param array $new_ids
	 * @param array $current_ids
	 * @return \KodiCMS_ORM
	 */
	public function update_related_ids($alias, array $new_ids = NULL, array $current_ids = NULL)
	{
		if (!is_array($new_ids))
		{
			return $this;
		}

		if (!$this->loaded() AND ! empty($new_ids))
		{
			return $this->add($alias, $new_ids);
		}

		if (empty($current_ids))
		{
			$current_ids = $this->get_related_ids($alias);
		}

		$old_ids = array_diff($current_ids, $new_ids);
		$new_ids = array_diff($new_ids, $current_ids);

		if (!empty($old_ids))
		{
			$this->remove($alias, $old_ids);
		}

		if (!empty($new_ids))
		{
			$this->add($alias, $new_ids);
		}

		return $this;
	}

	/**
	 * Updates a single record or multiple records
	 *
	 * @chainable
	 * @param  Validation $validation Validation object
	 * @throws Kohana_Exception
	 * @return ORM
	 */
	public function create(Validation $validation = NULL)
	{
		if (!$this->before_save())
		{
			return FALSE;
		}
		
		if (!$this->before_create())
		{
			return FALSE;
		}

		parent::create($validation);

		$this->after_create();
		$this->after_save();

		return $this;
	}

	/**
	 * Updates or Creates the record depending on loaded()
	 *
	 * @chainable
	 * @param  Validation $validation Validation object
	 * @return ORM
	 */
	public function update(Validation $validation = NULL)
	{
		if (!$this->before_save())
		{
			return FALSE;
		}
		
		if (!$this->before_update())
		{
			return FALSE;
		}

		parent::update($validation);

		$this->after_update();
		$this->after_save();

		return $this;
	}

	/**
	 * Deletes a single record while ignoring relationships.
	 *
	 * @chainable
	 * @throws Kohana_Exception
	 * @return ORM
	 */
	public function delete()
	{
		if (!$this->before_delete())
		{
			return FALSE;
		}

		$id = $this->pk();

		parent::delete();

		$this->after_delete($id);

		return $this;
	}

	/**
	 * 
	 * @param array $tags
	 * @return \KodiCMS_ORM
	 */
	public function cache_tags( array $tags )
	{
		// Add pending database call which is executed after query type is determined
		$this->_db_pending[] = array(
			'name' => 'cache_tags',
			'args' => array( $tags ),
		);

		return $this;
	}
		
	public function before_save()	{ return TRUE; }
    public function before_create() { return TRUE; }
    public function before_update() { return TRUE; }
    public function before_delete() { return TRUE; }

    public function after_save()	{}
    public function after_create()	{}
    public function after_update()	{}
    public function after_delete( $id )	{}
}
