<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Datasource_Data_Manager {
	
	/**
	 * 
	 * @var integer
	 */
	public static $first_section = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected static $_cache = array();

	/**
	 * Список всех типов разделв
	 * 
	 * @return array
	 */
	public static function types()
	{
		return Config::get('datasources.types');
	}
	
	/**
	 * Загрузить дерево всех разделов
	 * Если есть разделы, модули для которых отключены, они будут игнорироваться
	 * 
	 * @return array array([Type][ID] => Datasource_Section)
	 */
	public static function get_tree( $type = NULL )
	{
		$result = array();
		
		$sections = self::get_all($type);

		foreach ( $sections as $section )
		{
			if( ! Datasource_Section::exists($section->type()))
			{
				continue;
			}

			if( self::$first_section === NULL )
			{
				self::$first_section = $section->id();
			}

			$result[$section->type()][$section->id()] = $section;
		}
		
		return $result;
	}
	
	/**
	 * Получить список всех разделов
	 * 
	 * @param	string	$type Фильтрация по типу разделов
	 * @return	array array([ID] => Datasource_Section, ...)
	 */
	public static function get_all($type = NULL)
	{
		if(is_array($type))
		{
			if(empty($type))
			{
				return array();
			}
		}
		else if($type !== NULL)
		{
			$type = array($type);
		}
		
		$cache_key = $type === NULL ? 'all' : implode('::', $type);
	
		if(isset(self::$_cache[$cache_key]))
		{
			return self::$_cache[$cache_key];
		}

		$query = DB::select()
			->from('datasources')
			->order_by('type')
			->order_by('name');
		
		if($type !== NULL)
		{
			$query->where('type', 'in', $type);
		}
		
		$db_sections = $query->execute()->as_array('id');
		$sections = array();

		foreach ($db_sections as $id => $section)
		{
			if (!Datasource_Section::exists($section['type']))
			{
				continue;
			}
	
			$sections[$id] = Datasource_Section::load_from_array($section);
		}
		
		self::$_cache[$cache_key] = $sections;

		return $sections;
	}
	
	/**
	 * Получение списка разделов для выпадающего списка
	 * @return array
	 */
	public static function get_all_as_options( $type = NULL )
	{
		$datasources = self::get_all($type);
		
		$options = array(__('--- Not set ---'));
		foreach ($datasources as $section)
		{
			$options[$value['id']] = $section->name;
		}

		return $options;
	}


	/**
	 * Загрузка разедла по ID
	 * 
	 * @param integer $id
	 * @return null|Datasource_Section
	 */
	public static function load($id)
	{
		return Datasource_Section::load($id);
	}

	/**
	 * Проверка раздела на существование по ID
	 * 
	 * @param integer $ds_id	Datasource ID
	 * @return boolean
	 */
	public static function exists($ds_id) 
	{
		return (bool) DB::select('id')
			->from('datasources')
			->where('id', '=', (int) $ds_id)
			->limit(1)
			->execute()
			->get('id');
	}
	
	/**
	 * 
	 * @param integer $ds_id Datasource ID
	 * @return array
	 */
	public static function get_info($ds_id) 
	{
		return DB::select('id', 'type', 'name', 'description')
			->from(array('datasources', 'ds'))
			->where('ds.id', '=', (int) $ds_id)
			->limit(1)
			->execute()
			->current();
	}
	
	/**
	 * 
	 * @param string $type
	 * @return string
	 */
	public static function get_icon($type)
	{
		$class_name = 'DataSource_Section_' . ucfirst($type);
		
		if(class_exists($class_name))
		{
			return call_user_func($class_name . '::icon');
		}
		
		return Datasource_Section::icon();
	}
	
	/**
	 * 
	 * @param integer $ds_id
	 * @return array
	 */
	public static function clear_cache( $ds_id, array $widget_types = array() ) 
	{
		$objects = Widget_Manager::get_widgets($widget_types);
		
		$cleared_ids = array();
	
		foreach($objects as $id => $data)
		{
			$widget = Widget_Manager::load($id);
			if($widget->ds_id == $ds_id )
			{
				$cleared_ids[] = $widget->id;
				$widget->clear_cache();
			}
		}
		
		return $cleared_ids;
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function get_all_indexed() 
	{
		return DB::select('id', 'type', 'name', 'description')
			->from('datasources')
			->where('indexed', '!=', 0)
			->order_by('name')
			->execute()
			->as_array('id');
	}
}