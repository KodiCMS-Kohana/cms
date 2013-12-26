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
	public static $first = NULL;
	
	/**
	 * 
	 * @return array
	 */
	public static function types()
	{
		return Config::get('datasources.types');
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function get_tree()
	{
		$result = array();
		
		$query = DB::select(array('ds.ds_id', 'id'), array('ds_type', 'type'), 'name', 'description')
			->from(array('datasources', 'ds'))
			->order_by('ds_type')
			->order_by('name')
			->execute()
			->as_array('id');

		foreach ( $query as $r )
		{
			if( ! self::$first ) self::$first = $r['id'];

			$result[$r['type']][$r['id']] = array('name' => $r['name'], 'description' => $r['description']);
		}
		
		return $result;
	}
	
	/**
	 * @param	string	$type	Datasource type
	 * 
	 * @return	array
	 */
	public static function get_all($type = NULL) 
	{
		$sections = DB::select(array('ds.ds_id', 'id'), 'name', 'description')
			->select('parent', 'ds_type', 'internal')
			->from(array('datasources', 'ds'))
			->where('internal', '=', 0)
			->order_by('name');
		
		if($type !== NULL)
		{
			$sections->where('ds.ds_type', is_array($type) ? 'IN' : '=', $type);
		}

		 return $sections
			->execute()
			->as_array('id');
	}
	
	/**
	 * 
	 * @param integer $ds_id	Datasource ID
	 * 
	 * @return boolean
	 */
	public static function exists($ds_id) 
	{
		return (bool) DB::select('ds_id')
			->from('datasources')
			->where('ds_id', '=', (int) $ds_id)
			->limit(1)
			->execute()
			->get('ds_id');
	}
	
	/**
	 * 
	 * @param integer $ds_id Datasource ID
	 * @return array
	 */
	public static function get_info($ds_id) 
	{
		return DB::select(array('ds.ds_id', 'id'), array('ds_type', 'type'), 'name', 'description')
			->select('internal')
			->from(array('datasources', 'ds'))
			->where('ds.ds_id', '=', (int) $ds_id)
			->limit(1)
			->execute()
			->current();
	}
	
	/**
	 * @param indeger $ds_id Datasource ID
	 * @return Datasource_Section|DataSource_Data_Hybrid_Section
	 */
	public static function load($ds_id) 
	{
		return Datasource_Section::load($ds_id);
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
		return DB::select(array('ds_id', 'id'), array('ds_type', 'type'))
			->select('name', 'description')
			->from('datasources')
			->where('internal', '=', 0)
			->where('indexed', '!=', 0)
			->order_by('name')
			->execute()
			->as_array('id');
	}
}