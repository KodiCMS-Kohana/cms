<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi
 */

class Setting
{
	const TABLE_NAME = 'settings';

	/**
	 *
	 * @var array
	 */
	public static $settings = array();
	
	/**
	 *
	 * @var boolean 
	 */
    public static $is_loaded = FALSE;
    
	/**
	 * 
	 * @return array
	 */
    public static function init()
    {
        if (! self::$is_loaded)
        {
            self::$settings = DB::select()
				->from(self::TABLE_NAME)
				->cache_key(self::TABLE_NAME)
				->cached()
				->execute()
				->as_array('name', 'value');
            
            self::$is_loaded = true;
        }
		
		return self::$settings;
    }
    
    /**
     * Get the value of a setting
     *
     * @param name  string  The setting name
     * @return string the value of the setting name
     */
    public static function get($name, $default = NULL)
    {
        return Arr::get(self::$settings, $name, $default);
    }
    
	/**
	 * 
	 * @param array $data
	 */
    public static function saveFromData(array $data)
    {
        foreach( $data as $name => $value )
        {
			if(self::get($name) === NULL)
			{
				$query = DB::insert(self::TABLE_NAME)
					->columns(array('name', 'value'))
					->values(array($name, $value));
			}
			else 
			{
				$query = DB::update(self::TABLE_NAME)
					->set(array('value' => $value))
					->where('name', '=', $name);
			}
			
			$query->execute();
        }
		
		Kohana::cache('Database::cache('.self::TABLE_NAME.')', NULL, -1);
    }
} // end Setting class