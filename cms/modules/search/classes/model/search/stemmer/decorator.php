<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Search
 * @category	Stemmer
 * @author		butschster <butschster@gmail.com>
 */
class Model_Search_Stemmer_Decorator {
	
	protected static $instances = NULL;

	/**
	 * @return Model_Search_Stemmer_Decorator
	 */
	public static function instance()
	{
		if (!isset(static::$instances))
		{
			static::$instances = new static;
		}

		return static::$instances;
	}

}