<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Filter {

	static $filters = array( );
	private static $filters_loaded = array( );

	/**
	 * Add a new filter to Frog CMS
	 *
	 * @param filter_id string  The Filter plugin folder name
	 * @param file      string  The file where the Filter class is
	 */
	public static function add( $filter_id, $file )
	{
		self::$filters[$filter_id] = $file;
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
		return array_keys( self::$filters );
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
		if ( !isset( self::$filters_loaded[$filter_id] ) )
		{
			if ( isset( self::$filters[$filter_id] ) )
			{
				$file = PLGPATH . DIRECTORY_SEPARATOR . self::$filters[$filter_id];

				if ( !file_exists( $file ) )
				{
					throw new Core_Exception( 'Filter file of filter :filter not found!', array(
						':filter' => $filter_id
					) );
				}

				include($file);

				$filter_class = Inflector::camelize( $filter_id );
				self::$filters_loaded[$filter_id] = new $filter_class();
				return self::$filters_loaded[$filter_id];
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return self::$filters_loaded[$filter_id];
		}
	}

}

// end Filter class