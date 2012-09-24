<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Filter {

	static $filters = array( );

	/**
	 * Add a new filter to Frog CMS
	 *
	 * @param filter_id string  The Filter plugin folder name
	 * @param file      string  The file where the Filter class is
	 */
	public static function add( $filter_id)
	{
		self::$filters[$filter_id] = $filter_id;
	}

	/**
	 * Remove a filter to Frog CMS
	 *
	 * @param filter_id string  The Filter plugin folder name
	 */
	public static function remove( $filter_id )
	{
		if ( isset( self::$filters[$filter_id] ) )
		{
			unset( self::$filters[$filter_id] );
		}
	}

	/**
	 * Find all active filters id
	 *
	 * @return array
	 */
	public static function findAll()
	{
		return self::$filters;
	}

	/**
	 * Get a instance of a filter
	 *
	 * @param filter_id string  The Filter plugin folder name
	 *
	 * @return mixed   if founded an object, else FALSE
	 */
	public static function get( $filter_id )
	{
		if ( isset( self::$filters[$filter_id] ) )
		{
			if ( !class_exists( $filter_id ) )
			{
				return FALSE;
			}

			return new $filter_id;
		}
		else
		{
			return FALSE;
		}
	}

}

// end Filter class