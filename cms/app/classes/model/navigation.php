<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Navigation {

	protected static $_sections = array();
	
	public static $current = NULL;
	
	public static function get_section($name)
	{
		foreach (self::$_sections as $section)
		{
			if($section->id() == $name)
			{
				return $section;
			}
		}
		
		return NULL;
	}

	public static function add_section( $section = 'Other', $name, $uri, $permissions = array('administrator'), $priority = 0 )
	{
		if ( AuthUser::hasPermission( $permissions ) )
		{
			if ( ($section_object = self::get_section( $section )) === NULL )
			{
				$section_object = self::$_sections[] = new Model_Navigation_Section(array(
					'name' => $section
				));
			}
			
			$section_object->add_page(new Model_Navigation_Page(array(
				'name' => $name,
				'url' => URL::site($uri)
			)), $priority);
		}
	}
	
	static function get()
	{
		if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) )
		{
			asort(self::$_sections);
		}
		
		$break = FALSE;
		foreach ( self::$_sections as $section )
		{
			foreach ( $section->get_pages() as $page )
			{
				if ( strpos(Request::current()->uri(), ltrim($page->url(), '/')) !== FALSE )
				{
					$page->set_active();

					$break = TRUE;
					break;
				}
			}

			if ( $break )
				break;
		}

		return self::$_sections;
	}
}