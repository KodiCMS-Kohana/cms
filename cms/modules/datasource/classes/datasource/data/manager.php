<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Datasource
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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
	 * Добавление раздела в меню Backend
	 * 
	 * @param Datasource_Section $section
	 * @param Model_Navigation_Section $parent_section
	 * return Model_Navigation_Section;
	 */
	public static function add_section_to_menu(Datasource_Section $section, Model_Navigation_Section $parent_section = NULL)
	{	
		if ($parent_section === NULL)
		{
			$parent_section = Model_Navigation::get_root_section();
		}

		if (!$section->has_access_view())
		{
			return $parent_section;
		}

		return $parent_section
			->add_page(new Model_Navigation_Page(array(
				'name' => $section->name,
				'url' => Route::get('datasources')->uri(array(
					'controller' => 'data',
					'directory' => 'datasources',
				)) . URL::query(array('ds_id' => $section->id())),
				'icon' => $section->icon(),
				'permissions' => 'ds_id.' . $section->id() . '.section.view'
			)), 999);
	}

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

		foreach ($sections as $section)
		{
			if (!Datasource_Section::exists($section->type()))
			{
				continue;
			}

			if (self::$first_section === NULL)
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
		if (is_array($type))
		{
			if (empty($type))
			{
				return array();
			}
		}
		else if ($type !== NULL)
		{
			$type = array($type);
		}
		else
		{
			$type = array_keys(self::types());
		}

		$sections = array();

		foreach ($type as $i => $key)
		{
			if (isset(self::$_cache[$key]))
			{
				foreach (self::$_cache[$key] as $id => $section)
				{
					$sections[$id] = $section;
				}
				unset($type[$i]);
			}
		}

		if (empty($type))
		{
			return $sections;
		}

		$query = DB::select()
			->from('datasources')
			->order_by('type')
			->order_by('name');

		if ($type !== NULL)
		{
			$query->where('type', 'in', $type);
		}

		$db_sections = $query->execute()->as_array('id');

		foreach ($db_sections as $id => $section)
		{
			if (!Datasource_Section::exists($section['type']))
			{
				continue;
			}

			$section = Datasource_Section::load_from_array($section);
			$sections[$id] = $section;
			self::$_cache[$section->type()][$id] = $section;
		}

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
		foreach ($datasources as $id => $section)
		{
			$options[$id] = $section->name;
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

		if (class_exists($class_name))
		{
			return call_user_func($class_name . '::default_icon');
		}

		return Datasource_Section::default_icon();
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

		foreach ($objects as $id => $data)
		{
			$widget = Widget_Manager::load($id);
			if ($widget->ds_id == $ds_id)
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