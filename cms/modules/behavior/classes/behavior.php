<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi
 */
class Behavior
{
	/**
	 *
	 * @var array 
	 */
	private static $behaviors = array();

	/**
	 * Init behaviors
	 */
	public static function init()
	{
		$config = Kohana::$config->load('behaviors');

		
		foreach ( $config as $behavior_id => $data )
		{
			self::$behaviors[$behavior_id] = $data;
		}
	}

	/**
	 * 
	 * @param type $behavior_id
	 * @return array
	 */
	public static function get($behavior_id)
	{
		return isset(self::$behaviors[$behavior_id]) ? self::$behaviors[$behavior_id] : NULL;
	}

	/**
	 * Remove a behavior to Frog CMS
	 *
	 * @param behavior_id string  The Behavior plugin folder name
	 */
	public static function remove($behavior_id)
	{
		if (isset(self::$behaviors[$behavior_id]))
		{
			unset(self::$behaviors[$behavior_id]);
		}
	}


	/**
	 * Load a behavior and return it
	 *
	 * @param behavior_id string  The Behavior plugin folder name
	 * @param page        object  Will be pass to the behavior
	 * 
	 *
	 * @return object
	 */
	public static function load($behavior_id, &$page, $url, $uri)
	{
		$behavior = self::get($behavior_id);
	
		if( $behavior === NULL ) return NULL;

		$class = $behavior_id;
		if(isset($behavior['class']))
		{
			$class = $behavior['class'];
		}

		$behavior_class = 'Behavior_'.URL::title($class, '_');

		if (class_exists($behavior_class))
		{
			return new $behavior_class($page, $url, $uri);
		}
		
		throw new HTTP_Exception_404('Behavior :behavior not found!', array(
			':behavior' => $behavior_id
		));
	}


	/**
	 * Load a behavior and return it
	 *
	 * @param behavior_id string  The Behavior plugin folder name
	 *
	 * @return string   class name of the page
	 */
	public static function load_page($behavior_id)
	{
		$behavior_page_class = 'Model_Page_Behavior_' . URL::title($behavior_id, '_');

		if (class_exists($behavior_page_class))
		{
			return $behavior_page_class;
		}
		else
		{
			return 'Model_Page_Front';
		}
	}

	/**
	 *
	 * Find all active Behaviors id
	 * @return array
	 */
	public static function findAll()
	{
		return array_keys(self::$behaviors);
	}

} // end Behavior class