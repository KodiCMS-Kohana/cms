<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi
 */

class Behavior
{
    private static $loaded_files = array();
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
    
	
	public static function get($behavior_id)
	{
		return Arr::get(self::$behaviors, $behavior_id);
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
	 * @param params      array   Params that fallow the page with this behavior (passed to the behavior too)
	 *
	 * @return object
	 */
    public static function load($behavior_id, &$page, $params)
    {
		$behavior = self::get($behavior_id);
		if(!$behavior)
		{
			return NULL;
		}
		
		if(isset($behavior['file']))
		{
			$file = PLUGPATH . DIRECTORY_SEPARATOR . trim($behavior['file'], '/');
		}

		if (isset(self::$loaded_files[$file]))
		{
			return new $behavior_id($page, $params);
		}

		if (file_exists($file))
		{
			include $file;
			self::$loaded_files[$file] = TRUE;
			return new $behavior_id($page, $params);
		}
		else
		{
			throw new  Kohana_Exception('Behavior :behavior not found!', array(
				':behavior' => $behavior_id
			));
		}
    }
	
	
	/**
	 * Load a behavior and return it
	 *
	 * @param behavior_id string  The Behavior plugin folder name
	 *
	 * @return string   class name of the page
	 */
    public static function loadPageHack($behavior_id)
    {
        $behavior_page_class = 'Page'.str_replace(' ','',ucwords(str_replace('_',' ', $behavior_id)));
		
        if (class_exists($behavior_page_class, FALSE))
		{
            return $behavior_page_class;
		}
        else
		{
            return 'FrontPage';
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