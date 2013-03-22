<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Datasource
 */
class Widget_Manager {

	/**
	 * 
	 * @return array
	 */
	public static function map()
	{
		return Kohana::$config->load( 'widgets' )->as_array();
	}
	
	public static function get_empty_object( $type )
	{
		$class = 'Model_Widget_' . $type;

		$widget = new $class;
		$widget->type = $type;

		return $widget;
	}
	
	public static function get_widgets( $type )
	{
		$result = array( );

		$res = DB::select( 'w.id', 'w.name', 'w.description', 'w.date', 'w.type' )
				->select( array( DB::expr( 'COUNT(:table)' )->param(
							':table', Database::instance()->quote_column( 'pw.page_id' ) ), 'used' ) )
				->from( array( 'widgets', 'w' ) )
				->join( array( 'page_widgets', 'pw' ), 'left' )
				->on( 'w.id', '=', 'pw.widget_id' )
				->where( 'w.type', '=', $type )
				->group_by( 'w.id' )
				->group_by( 'w.name' )
				->order_by( 'w.name' )
				->execute();

		foreach( $res as $row )
		{
			$result[$row['id']] = array(
				'name' => $row['name'],
				'description' => $row['description'],
				'is_used' => $row['used'] > 0,
				'date' => $row['date']
			);
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public static function get_all_widgets()
	{
		return DB::select( 'id', 'type', 'name', 'description' )
				->from( 'widgets' )
				->order_by( 'type', 'asc' )
				->order_by( 'name', 'asc' )
				->execute('id');
	}

	/**
	 * 
	 * @param Model_Widget_Decorator $widget
	 * @return integer
	 * @throws HTTP_Exception_404
	 */
	public static function create( Model_Widget_Decorator $widget )
	{
		if( $widget->loaded() )
		{
			throw new HTTP_Exception_404( 'Widget created' );
		}

		$widget = ORM::factory('widget')
			->values( array(
				'type' => $widget->type,
				'name' => $widget->name,
				'description' => $widget->description,
				'code' => serialize($widget)
			))
			->create();

		return $widget->id;
	}

	/**
	 * 
	 * @param Widget_Decorator $widget
	 * @return integer
	 * @throws HTTP_Exception_404
	 */
	public static function update( Model_Widget_Decorator $widget )
	{
		$orm_widget = ORM::factory('widget', $widget->id )
			->values(array(
				'type' => $widget->type,
				'name' => $widget->name,
				'template' => $widget->template,
				'description' => $widget->description,
				'code' => serialize($widget)
			))
			->update();
		
		$widget->clear_cache();

		return $orm_widget->id;
	}

	/**
	 * 
	 * @param array $ids
	 * @return type
	 */
	public static function remove( array $ids )
	{
		return DB::delete( 'widgets' )
			->where( 'id', 'in', $ids )
			->execute();
	}

	/**
	 * 
	 * @param integer $id
	 * @return Model_Widget_Decorator
	 */
	public static function load( $id )
	{
		$result = DB::select()
			->from( 'widgets' )
			->where( 'id', '=', (int) $id )
			->limit( 1 )
			->execute()
			->current();

		if( ! $result )
		{
			return NULL;
		}

		$widget = unserialize( $result['code'] );
		$widget->id = $result['id'];

		return $widget;
	}

}