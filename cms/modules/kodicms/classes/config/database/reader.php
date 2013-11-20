<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Transparent extension of the Kohana_Config_Database_Reader class
 *
 * @package    Kohana/Database
 * @category   Configuration
 * @author     Kohana Team
 * @copyright  (c) 2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Config_Database_Reader extends Kohana_Config_Database_Reader
{
	/**
	 * Tries to load the specificed configuration group
	 *
	 * Returns FALSE if group does not exist or an array if it does
	 *
	 * @param  string $group Configuration group
	 * @return boolean|array
	 */
	public function load($group)
	{
		/**
		 * Prevents the catch-22 scenario where the database config reader attempts to load the 
		 * database connections details from the database.
		 *
		 * @link http://dev.kohanaframework.org/issues/4316
		 */
		if ($group === 'database')
			return FALSE;

		$query = DB::select('config_key', 'config_value')
			->from($this->_table_name)
			->where('group_name', '=', $group)
			->cache_key('config.group.' . $group)
			->cached(Date::MONTH)
			->execute($this->_db_instance);

		return count($query) ? array_map('unserialize', $query->as_array('config_key', 'config_value')) : FALSE;
	}
}
