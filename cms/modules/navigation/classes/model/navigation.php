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
	 * @param array $sitemap
	 */
	public static function create(array $sitemap)
	{
		foreach ($sitemap as $section => $pages)
		{
			$section = self::get_section($section);
			
			foreach ($pages as $page)
			{
				$page = new Model_Navigation_Page($page);
				
				if(ACL::check( $page->permissions )) 
					$section->add_page( $page );
			}
		}
	}

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
		
		$section = new Model_Navigation_Section(array(
			'name' => $name
		));
		
		self::$_sections[] = $section;
		
		return $section;
	}

	/**
	 * 
	 * @param string $section
	 * @param string $name
	 * @param string $uri
	 * @param array $permissions
	 * @param integer $priority
	 */
	public static function add_section( $section = 'Other', $name, $uri, $priority = 0, $counter = 0 )
	{
		self::get_section( $section )
			->add_page(new Model_Navigation_Page(array(
				'name' => $name,
				'url' => $uri,
				'counter' => (int) $counter,
			)), $priority);
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function get($uri = NULL)
	{
		self::sort();
		
		if($uri === NULL)
		{
			$uri = Request::current()->uri();
		}
		
		if($uri == ADMIN_DIR_NAME) $uri .= '/' . Setting::get('default_tab');

		$uri = strtolower($uri);

		$break = FALSE;
		foreach ( self::$_sections as $section )
		{
			foreach ( $section->get_pages() as $page )
			{
				if ( strpos($uri, ltrim($page->url(), '/')) !== FALSE )
				{
					$page->set_active();
					
					self::$current = & $page;

					$break = TRUE;
					break;
				}
			}

			if ( $break )
				break;
		}
		return self::$_sections;
	}
	
	/**
	 * 
	 * @param string $uri
	 * @param array $data
	 */
	public static function update($uri, array $data)
	{
		$page = self::find_page_by_uri($uri);
		
		if($page instanceof Model_Navigation_Page)
		{
			foreach ($data as $key => $value)
			{
				$page->{$key} = $value;
			}
		}
	}

	/**
	 * 
	 * @param string $uri
	 * @return NULL|Model_Navigation_Page
	 */
	public static function & find_page_by_uri($uri)
	{
		$_page = NULL;
		foreach ( self::$_sections as $section )
		{
			if( $page = $section->find_page_by_uri( $uri ) )
			{
				return $page;
			}
		}
		
		return $_page;
	}

	public static function sort()
	{
		uasort(self::$_sections, function($a, $b)
		{
			if ($a->id() == $b->id()) 
			{
				return 0;
			}

			return ($a->id() < $b->id()) ? -1 : 1;
			
		});
	}
}