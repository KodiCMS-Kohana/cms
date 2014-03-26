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
	 * 
	 * @return array array([Type][ID] => array('name' => ..., 'description' => ....))
	 */
	public static function get_tree( $type = NULL )
	{
		$result = array();
		
		$sections = self::get_all($type);

		foreach ( $sections as $section )
		{
			if( self::$first_section === NULL )
			{
				self::$first_section = $section['id'];
			}

			$result[$section['type']][$section['id']] = array(
				'name' => $section['name'], 
				'description' => $section['description']
			);
		}
		
		return $result;
	}
	
	/**
	 * Получить список всех разделов
	 * 
	 * @param	string	$type Фильтрация по типу разделов
	 * @return	array array([ID] => array('id' => ..., 'name' => ...., 'type' => ...., 'description' => ....), ...)
	 */
	public static function get_all( $type = NULL) 
	{
		if(is_array($type) AND empty($type)) return array();

		$sections = DB::select('id', 'name', 'type', 'description')
			->from(array('datasources', 'ds'))
			->order_by('type')
			->order_by('name');
		
		if($type !== NULL)
		{
			$sections->where('ds.type', is_array($type) ? 'IN' : '=', $type);
		}

		return $sections
			->execute()
			->as_array('id');
	}


	/**
	 * Загрузка разедла по ID
	 * 
	 * @param integer $id
	 * @return null|Datasource_Section
	 */
	public static function load( $id ) 
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