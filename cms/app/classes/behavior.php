<?php defined('SYSPATH') or die('No direct access allowed.');

class Behavior
{
    private static $loaded_files = array();
    private static $behaviors = array();
    
	/**
	 * Add a new behavior to Frog CMS
	 *
	 * @param behavior_id string  The Behavior plugin folder name
	 * @param file      string  The file where the Behavior class is
	 */
    public static function add($behavior_id, $file)
    {
        self::$behaviors[$behavior_id] = $file;
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
        if ( ! empty(self::$behaviors[$behavior_id]))
        {
            $file = PLUGPATH.DIRECTORY_SEPARATOR.self::$behaviors[$behavior_id];

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