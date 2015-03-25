<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Widget_Manager {
	
	const CACHE_DEFAULT = 'default';
	
	private static $_cache = array();

	/**
	 * Получение всех типов виджетов из конфига
	 * @return array
	 */
	public static function map($type = 'widgets')
	{
		return Kohana::$config->load($type)->as_array();
	}
	
	/**
	 * Фабрика для создания виджета.
	 * 
	 * @param string $type Тип виджеа
	 * @return Model_Widget_Decorator
	 */
	public static function factory($type)
	{
		$class = 'Model_Widget_' . $type;

		if (!class_exists($class))
		{
			throw new Kohana_Exception('Widget :type not exists', array(':type' => $type));
		}

		$widget = new $class;

		return $widget;
	}

	/**
	 * Получения списка виджетов по их типу
	 * 
	 * @param array $types Тип виджета
	 * @return array array([ID] => Model_Widget_Decorator, ....)
	 */
	public static function get_widgets(array $types = NULL)
	{
		$result = array();

		$query = DB::select('w.*')
			->select(array(DB::expr('COUNT(:table)')->param(':table', Database::instance()->quote_column('pw.page_id')), 'used'))
			->from(array('widgets', 'w'))
			->join(array('page_widgets', 'pw'), 'left')
			->on('w.id', '=', 'pw.widget_id')
			->group_by('w.id')
			->order_by('w.name');

		if (!empty($types))
		{
			$query->where('w.type', 'in', $types);
		}

		foreach ($query->execute()->as_array('id') as $id => $widget)
		{
			if (!self::exists_by_type($widget['type']))
			{
				continue;
			}

			$result[$id] = $widgets[$id] = self::load_from_array($widget);
		}

		return $result;
	}

	/**
	 * Получение списка всех виджетов
	 * 
	 * @return array array([ID] => array([ID], [TYPE], [NAME], [DESCRIPTION]), ...)
	 */
	public static function get_all_widgets()
	{
		return DB::select('id', 'type', 'name', 'description')
			->from('widgets')
			->order_by('type', 'asc')
			->order_by('name', 'asc')
			->execute()
			->as_array('id');
	}
	
	/**
	 * Получение списка виджетов по ID страницы
	 * 
	 * @cache Date::DAY
	 * @cache_key layout_blocks_[PAGE_ID]
	 * @param integer $page_id
	 * @return array @return array array([ID] => Model_Widget_Decorator, ....)
	 */
	public static function get_widgets_by_page($page_id)
	{
		$result = DB::select('page_widgets.block', 'page_widgets.position')
			->select('widgets.*')
			->from('page_widgets')
			->join('widgets')
			->on('widgets.id', '=', 'page_widgets.widget_id')
			->where('page_id', '=', (int) $page_id)
			->order_by('page_widgets.block', 'ASC')
			->order_by('page_widgets.position', 'ASC')
			->cache_tags(array('layout_blocks'))
			->cache_key('layout_blocks_' . (int) $page_id)
			->cached(Date::DAY)
			->execute()
			->as_array('id');

		$widgets = array();
		foreach ($result as $id => $widget)
		{
			if (!self::exists_by_type($widget['type']))
			{
				continue;
			}

			$widgets[$id] = self::load_from_array($widget, __METHOD__);
		}

		return $widgets;
	}

	/**
	 * Копирование списка виджетов с одной страницы на другую
	 * 
	 * @param integer $from_page_id
	 * @param integer $to_page_id
	 * @return boolean
	 */
	public static function copy($from_page_id, $to_page_id)
	{
		$select = DB::select(array(DB::expr("IF(`pw2`.`page_id` IS NULL, '{$to_page_id}', `pw2`.`page_id`)"), 'page_id'), 'pw1.widget_id', 'pw1.block', 'pw1.position')
			->from(array('page_widgets', 'pw1'))
			->join(array('page_widgets', 'pw2'), 'left')
				->on('pw2.page_id', '=', DB::expr($to_page_id))
				->on('pw1.widget_id', '=', 'pw2.widget_id')
			->where('pw1.page_id', '=', (int) $from_page_id)
			->where('pw2.page_id', '=', NULL);

		DB::insert('page_widgets')
			->select($select)
			->columns(array('page_id', 'widget_id', 'block', 'position'))
			->execute();
		
		return TRUE;
	}

	/**
	 * Добавление виджета в БД
	 *  
	 * @param Model_Widget_Decorator $widget
	 * @return integer ID виджета
	 * @throws HTTP_Exception_404
	 */
	public static function create(Model_Widget_Decorator $widget)
	{
		if ($widget->loaded())
		{
			throw new HTTP_Exception_404('Widget created');
		}

		$widget = ORM::factory('widget')
			->values(array(
				'type' => $widget->type(),
				'name' => $widget->name,
				'description' => $widget->description,
				'code' => Kohana::serialize($widget)
			))
			->create();

		return $widget->id;
	}

	/**
	 * Обновление виджета
	 * При обновлении виджета происходит вызов метода clear_cache() 
	 * для очистки кеша у виджета
	 * 
	 * @param Widget_Decorator $widget
	 * @return integer
	 * @throws HTTP_Exception_404
	 */
	public static function update(Model_Widget_Decorator $widget)
	{
		$orm_widget = ORM::factory('widget', $widget->id)
			->values(array(
				'type' => $widget->type(),
				'name' => $widget->name,
				'template' => $widget->template,
				'description' => $widget->description,
				'code' => Kohana::serialize($widget)
			))
			->update();

		$widget->clear_cache();

		return $orm_widget->id;
	}

	/**
	 * Удаление списка виджетов по ID
	 * 
	 * @param array $ids array([ID], [ID2])
	 * @return type
	 */
	public static function remove(array $ids)
	{
		return DB::delete('widgets')
			->where('id', 'in', $ids)
			->execute();
	}

	/**
	 * Получение виджета по ID
	 * 
	 * @param integer $id
	 * @return NULL|Model_Widget_Decorator
	 */
	public static function load($id)
	{
		if (isset(self::$_cache[$id]))
		{
			return clone self::$_cache[$id];
		}

		$result = DB::select()
			->from('widgets')
			->where('id', '=', (int) $id)
			->limit(1)
			->execute()
			->current();

		return self::load_from_array($result);
	}

	/**
	 * Размещение виджета на страницах
	 * 
	 * @param integer $widget_id Идентификатор
	 * @param array $data array([PAGE_ID] => [BLOCK NAME], ....)
	 * @observer widget_set_location
	 */
	public static function set_location($widget_id, array $data)
	{
		DB::delete('page_widgets')
			->where('widget_id', '=', (int) $widget_id)
			->execute();

		if (!empty($data))
		{
			$page_ids = array();
			
			$insert = DB::insert('page_widgets')
				->columns(array('page_id', 'widget_id', 'block', 'position'));

			$i = 0;
			foreach ($data as $page_id => $block)
			{
				if ($block['name'] == -1)
				{
					continue;
				}

				$page_ids[] = $page_id;
				$insert->values(array($page_id, (int) $widget_id, $block['name'], (int) $block['position']));
				$i++;
			}

			if ($i > 0)
			{
				$insert->execute();
			}

			Observer::notify('widget_set_location', $page_ids);
		}
	}
	
	/**
	 * Обновление позици виджета на странице
	 * 
	 * При передачи названия блока есть два системных состояния
	 * 0 - Скрытый виджет
	 * -1 - Удалить со страницы
	 * 
	 * @param integer $page_id
	 * @param integer $widget_id
	 * @param array $data array(['block'] => [String], 'position' => [Integer])
	 */
	public static function update_location_by_page($page_id, $widget_id, array $data)
	{
		if ($data['block'] < 0)
		{
			DB::delete('page_widgets')
				->where('widget_id', '=', (int) $widget_id)
				->where('page_id', '=', (int) $page_id)
				->execute();
		}
		else
		{
			DB::update('page_widgets')
				->where('widget_id', '=', (int) $widget_id)
				->where('page_id', '=', (int) $page_id)
				->set(array('block' => $data['block'], 'position' => (int) $data['position']))
				->execute();
		}

		Observer::notify('widget_set_location', array((int) $page_id));
	}
	
	/**
	 * Усмтановка виджета из массива
	 * 
	 * array(
	 *		'type' => [Widget type],
	 *		'data' => array (
	 *			[KEY] => [VALUE]
	 *			.....
	 *		),
	 *		'blocks' => array (
	 *			[PAGE_ID] => [BLOCK NAME]
	 *		)
	 *	)
	 * 
	 * @param array $widget_array
	 * @return integer $id
	 */
	public static function install(array $widget_array)
	{
		if ( 
			empty($widget_array['type']) 
		OR 
			empty($widget_array['data'])
		OR 
			empty($widget_array['data']['name'])) 
		{
			return;
		}

		$widget = Widget_Manager::factory($widget_array['type']);
		
		try 
		{
			$widget->name = $widget_array['data']['name'];
			$widget->description = Arr::get($widget_array, 'description');

			$widget->set_values($widget_array['data']);
			$widget->set_cache_settings($widget_array['data']);

			$id = Widget_Manager::create($widget);
		}
		catch (Exception $e)
		{
			return FALSE;
		}
		
		$blocks = array();
		foreach (Arr::get($widget_array, 'blocks', array()) as $page_id => $block_name)
		{
			$blocks[$page_id] = array('name' => $block_name, 'position' => 500);
		}
		
		Widget_Manager::set_location($id, $blocks);
		
		return $id;
	}
	
	/**
	 * Получение списка блоков по умолчанию
	 * 
	 * @return array
	 */
	public static function get_system_blocks()
	{
		return array(
			-1		=> __('--- Remove from page ---'), 
			0		=> __('--- Hide ---'), 
			'PRE'	=> __('Before page render'), 
			'POST'	=> __('After page render')
		);
	}

	/**
	 * 
	 * @param string $layout_name
	 * 
	 * array(
	 *		'[layout name]' => array(
	 *			[block name] => [block name],
	 *			...
	 *		)
	 * )
	 * 
	 * @return array
	 */
	public static function get_blocks_by_layout($layout_name = NULL)
	{
		$blocks_by_layout = array();
		$database_blocks = ORM::factory('layout_block');

		if ($layout_name !== NULL)
		{
			$database_blocks->where('layout_name', '=', $layout_name);
		}

		foreach ($database_blocks->find_all() as $block)
		{
			if (empty($blocks_by_layout[$block->layout_name]))
			{
				$blocks_by_layout[$block->layout_name] = self::get_system_blocks();
			}

			$blocks_by_layout[$block->layout_name][$block->block] = $block->block;
		}

		// Move POST key to end
		foreach ($blocks_by_layout as $layout_name => $blocks)
		{
			$post = $blocks['POST'];
			unset($blocks_by_layout[$layout_name]['POST']);
			$blocks_by_layout[$layout_name]['POST'] = $post;
		}

		$layout_files = Model_File_Layout::find_all();
		foreach ($layout_files as $file)
		{
			if (!isset($blocks_by_layout[$file->name]))
			{
				$blocks_by_layout[$file->name] = self::get_system_blocks();
			}
		}

		return $blocks_by_layout;
	}

	/**
	 * 
	 * @param string $type
	 * @return boolean
	 */
	public static function exists_by_type($type)
	{
		$class = 'Model_Widget_' . ucfirst($type);

		return class_exists($class);
	}

	/**
	 * 
	 * @param array $types
	 * @param integer $ds_id
	 * @return array
	 */
	public static function get_related(array $types, $ds_id = NULL)
	{
		$db_widgets = Widget_Manager::get_widgets($types);

		$widgets = array();
		foreach ($db_widgets as $id => $widget)
		{
			if ($ds_id !== NULL AND $ds_id != $widget->ds_id)
			{
				continue;
			}

			$widgets[$id] = $widget->name;
		}

		return $widgets;
	}

	/**
	 * 
	 * @param string $type
	 * @return array
	 */
	public static function get_params($type)
	{
		$class = 'Model_Widget_' . ucfirst($type);

		$reflector = new ReflectionClass($class);
		$comments = $reflector->getMethod('fetch_data')->getDocComment();

		$params = array();

		if (!empty($comments))
		{
			$comments = str_replace(array('/', '*', "\t", "\n", "\r", ' '), '', $comments);
			preg_match_all("/\[(?s)(?m)(.*)\]/i", $comments, $found);

			if (!empty($found[1]))
			{
				$params = explode(',', $found[1][0]);
			}
		}

		$params[] = '$params';
		$params[] = '$ctx';
		$params[] = '$widget_id';
		$params[] = '$header';

		return $params;
	}

	/**
	 * 
	 * @param array $data
	 * @return Model_Widget_Decorator
	 */
	public static function load_from_array(array $data)
	{
		if (empty($data) OR ! self::exists_by_type($data['type']))
		{
			return NULL;
		}

		$widget = Kohana::unserialize($data['code']);
		unset($data['code'], $data['type']);

		foreach ($data as $key => $value)
		{
			$widget->{$key} = $value;
		}
		
		self::$_cache[$widget->id] = $widget;
		
		return $widget;
	}
}