<?php defined('SYSPATH') or die('No direct script access.');

/**
 * PDO_SQLite database connection.
 *
 * @package    Kohana
 * @author     Dinesh Shah and Tiger's Way
 * @copyright  (c) 2010-2011 Dinesh Shah and Tiger's Way
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Database_PDO_SQLite extends Kohana_Database_PDO {

	public function set_charset($charset)
	{

	}

	public function list_tables($like = NULL)
	{
		if (is_string($like))
		{
			// Search for table names
			$result = $this->query(Database::SELECT, 'SELECT name FROM SQLITE_MASTER WHERE type="table" AND name LIKE '.$this->quote($like).' ORDER BY name', FALSE);
		}
		else
		{
			// Find all table names
			$result = $this->query(Database::SELECT, 'SELECT name FROM SQLITE_MASTER WHERE type="table" ORDER BY name', FALSE);
		}

		$tables = array();
		foreach ($result as $row)
		{
			// Get the table name from the results
			$tables[] = current($row);
		}

		return $tables;
	}

	public function list_columns($table, $like = NULL, $add_prefix = TRUE)
	{
		if (is_string($like))
		{
			throw new Kohana_Exception('Database method :method is not supported by :class',
				array(':method' => __FUNCTION__, ':class' => __CLASS__));
		}
		
		// Find all column names
		$result = $this->query(Database::SELECT, 'PRAGMA table_info('.$table.')', FALSE);
		
		$columns = array();
		foreach ($result as $row)
		{
			// Get the column name from the results
			$columns[$row['name']] = $row['name'];
		}

		return $columns;
	}
	
	public function disable_foreign_key_checks()
	{
		return $this->query(NULL, 'PRAGMA foreign_keys = OFF');
	}

	public function enable_foreign_key_checks($db = NULL)
	{
		return $this->query(NULL, 'PRAGMA foreign_keys = ON');
	}

}// End Database_PDOSQLite