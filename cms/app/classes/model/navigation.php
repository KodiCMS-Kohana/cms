<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Navigation
 */

class Model_Navigation {

	/**
	 *
	 * @var array
	 */
	protected static $_sections = array();
	
	/**
	 *
	 * @var Model_Navigation_Page 
	 */
	public static $current = NULL;
	
	/**
	 * 
	 * @param string $name
	 * @return Model_Navigation_Section
	 */
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

	/**
	 * 
	 * @param string $section
	 * @param string $name
	 * @param string $uri
	 * @param array $permissions
	 * @param integer $priority
	 */
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
	
	/**
	 * 
	 * @return array
	 */
	public static function get()
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
					
					self::$current = $page;

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