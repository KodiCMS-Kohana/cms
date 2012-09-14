<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Navigation {
	
	protected static $_navigation = array();

	public static function add_section( $section = 'Other', $name, $uri, $permissions = array('administrator'), $priority = 0 )
	{
		$uri = URL::site($uri);
		$priority = (int) $priority;

		if ( AuthUser::hasPermission( $permissions ) )
		{
			if ( !isset( self::$_navigation[$section] ) )
			{
				self::$_navigation[$section] = (object) array(
					'is_current' => FALSE,
					'items' => array( )
				);
			}

			if ( isset( self::$_navigation[$section]->items[$priority] ) )
			{
				while ( isset( self::$_navigation[$section]->items[$priority] ) )
				{
					$priority++;
				}
			}

			self::$_navigation[$section]->items[$priority] = (object) array(
				'name' => $name,
				'uri' => $uri,
				'is_current' => FALSE,
				'priority' => $priority
			);
			
			ksort(self::$_navigation[$section]->items);
		}
	}
	
	static function get()
	{
		asort(self::$_navigation);
		$break = FALSE;
		foreach ( self::$_navigation as $key => $section )
		{
			ksort($section->items);

			foreach ( $section->items as $item_key => $item )
			{
				if ( strpos(Request::current()->uri(), ltrim($item->uri, '/')) !== FALSE )
				{
					self::$_navigation[$key]->is_current = TRUE;
					self::$_navigation[$key]->items[$item_key]->is_current = TRUE;
					$break = TRUE;
					break;
				}
			}

			if ( $break )
				break;
		}

		return self::$_navigation;
	}
}