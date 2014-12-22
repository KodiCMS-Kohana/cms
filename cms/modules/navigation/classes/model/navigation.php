<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Navigation
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Navigation {

	/**
	 *
	 * @var Model_Navigation_Section
	 */
	protected static $_root_section = NULL;
	
	/**
	 *
	 * @var Model_Navigation_Page 
	 */
	public static $current = NULL;
	
	/**
	 * 
	 * @param array $sitemap
	 */
	public static function init(array $sitemap)
	{
		foreach ($sitemap as $section)
		{
			if(!isset($section['name'])) continue;
			
			if(isset($section['url']))
			{
				$section_object = self::get_root_section();

				$page = new Model_Navigation_Page($section);
				$section_object->add_page($page);
			}
			else
			{
				$section_object = self::get_section($section['name']);
				if(isset($section['icon']))
				{
					$section_object->icon = $section['icon'];
				}

				if(isset($section['priority']))
				{
					$section_object->priority = (int) $section['priority'];
				}

				if(!empty($section['children']))
				{
					$section_object->add_pages($section['children']);
				}
			}
		}
	}

	/**
	 * 
	 * @param string $name
	 * @return Model_Navigation_Section
	 */
	public static function get_section($name, Model_Navigation_Section $parent = NULL, $priority = 1)
	{
		if($parent === NULL)
		{
			$parent = self::get_root_section();
		}

		$section = $parent->find_section($name);

		if( $section === NULL )
		{
			$section = new Model_Navigation_Section(array(
				'name' => $name,
				'priority' => $priority
			));

			$parent->add_page($section);
		}
		
		return $section;
	}
	
	public static function get_root_section()
	{
		if(self::$_root_section === NULL)
		{
			self::$_root_section = new Model_Navigation_Section(array(
				'name' => 'root'
			));
		}
		
		return self::$_root_section;
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
		if($uri === NULL)
		{
			$uri = Request::current()->uri();
		}

		$uri = strtolower($uri);

		$break = FALSE;
		
		self::$_root_section->find_active_page_by_uri($uri);
		self::$_root_section->sort();
		return self::$_root_section;
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
		foreach ( self::$_root_section->sections() as $section )
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
		uasort(self::$_root_section->sections(), function($a, $b)
		{
			if ($a->id() == $b->id()) 
			{
				return 0;
			}

			return ($a->id() < $b->id()) ? -1 : 1;
		});
	}
}