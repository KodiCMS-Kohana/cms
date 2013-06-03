<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */

class KodiCMS_Model_Permission extends Record
{
    const TABLE_NAME = 'roles';
	
	public static function get_all()
	{
		return DB::select('id', 'name')
			->from( Model_Permission::tableName() )
			->execute()
			->as_array('id', 'name');
	}
} // end class KodiCMS_Model_Permission