<?php defined('SYSPATH') or die('No direct script access.');

define('PDODIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sqlite-integration' . DIRECTORY_SEPARATOR);

/**
 * PDO_SQLite database connection.
 *
 * @package		Kohana/Database
 * @category	Drivers
 * @author		Dinesh Shah and Tiger's Way
 * @copyright	(c) 2010-2011 Dinesh Shah and Tiger's Way
 * @license		http://kohanaphp.com/license.html
 */
class Kohana_Database_PDO_SQLite extends Kohana_Database_PDO {

	public function connect()
	{
		if ($this->_connection)
		{
			return;
		}

		// Extract the connection parameters, adding required variabels
		extract($this->_config['connection'] + array(
			'dsn'        => '',
			'username'   => NULL,
			'password'   => NULL,
			'persistent' => FALSE,
		));

		// Extract file path
		$path = str_ireplace('sqlite:', '', $dsn);
		$dir = dirname($path);

		if (!is_dir($dir) OR ! is_writable($dir))
		{
			throw new Database_Exception(':dir is not a directory or is not writable', array(
				':dir' => $dir), 0);
		}

		// Clear the connection parameters for security
		unset($this->_config['connection']);

		// Force PDO to use exceptions for all errors
		$attrs = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

		if (!empty($persistent))
		{
			// Make the connection persistent
			$attrs[PDO::ATTR_PERSISTENT] = TRUE;
		}

		try
		{
			// Create a new PDO connection
			$this->_connection = new PDO($dsn, $username, $password, $attrs);
		} 
		catch (PDOException $e)
		{
			throw new Database_Exception('[:code] :error', array(
				':code' => $e->getCode(),
				':error' => $e->getMessage()), (int) $e->getCode());
		}
	}

	/**
	 * 
	 * @return string
	 */
	public function get_sqlite_version()
	{
		try
		{
			$statement = $this->_connection->prepare('SELECT sqlite_version()');
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_NUM);
			return $result[0];
		} 
		catch (PDOException $err)
		{
			return 0;
		}
	}
	
	public function set_charset($charset)
	{

	}
	
	public function datatype($type)
	{
		// SQLite data types are uppercase
		$type = strtolower($type);
		return parent::datatype($type);
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
		// Quote the table name
		$table = ($add_prefix === TRUE) ? $this->quote_table($table) : $table;
		if (is_string($like))
		{
			throw new Kohana_Exception('Database method :method with LIKE param is not supported by :class', 
					array(':method' => __FUNCTION__, ':class' => __CLASS__));
		}
	
		// Find all column names
		$result = $this->query(Database::SELECT, 'PRAGMA table_info(' . $table . ')');
		$count = 0;
		$columns = array();

		foreach ($result as $row)
		{
			list($type, $length) = $this->_parse_type($row['type']);
			$column = $this->datatype($type);
			$column['column_name'] = $row['name'];
			$column['column_default'] = $row['dflt_value'];
			$column['data_type'] = $type;
			$column['is_nullable'] = ($row['notnull'] == '0');
			$column['ordinal_position'] = ++$count;

			$columns[$row['name']] = $column;
		}

		return $columns;
	}

	public function query($type, $sql, $as_object = false, array $params = NULL)
	{
		$result = preg_match('/^\\s*(SET|EXPLAIN|PRAGMA|SELECT\\s*FOUND_ROWS|SELECT|INSERT|UPDATE|REPLACE|DELETE|ALTER|CREATE|DROP\\s*INDEX|DROP|SHOW|DESCRIBE|DESC|TRUNCATE|OPTIMIZE|CHECK|ANALYZE)/i', $sql, $match);
		
		if ($result)
		{
			$query_type = strtolower($match[1]);
			switch ($query_type)
			{
				case 'pragma':
					break;
				case 'set':
					$pattern = '/^\\s*SET\\s*FOREIGN_KEY_CHECKS\\s*=\\s*(0|1)\\s*/im';
					if (preg_match($pattern, $sql, $match))
					{
						$sql = 'PRAGMA foreign_keys = ' . $match[1];
					}
					
					break;
				case 'show':
					$sql = $this->handle_show_tables($sql);
					$sql = $this->handle_show_create($sql);
					$sql = $this->handle_show_columns($sql);
					$sql = $this->handle_show_index($sql);
					
					break;
				case 'create':
					$engine = new Kohana_Database_PDO_SQLite_Create();
					$sql = join(";\n", $engine->rewrite_query($sql));
					
					break;
				case 'alter':
					$engine = new Kohana_Database_PDO_SQLite_Alter();
					$re_query = NULL;
					
					$rewritten_query = $engine->rewrite_query($sql, $query_type);
					if (is_array($rewritten_query) && array_key_exists('recursion', $rewritten_query))
					{
						$re_query = $rewritten_query['recursion'];
						unset($rewritten_query['recursion']);
						$rewritten_query[] = $re_query;
					}
					
					$sql = join(";\n", $rewritten_query);
					break;
				default:
					$sql = preg_replace(array(
							'/NOW\(\)/im',
							'/CURDATE\(\)\\s+(\+|-)\\s+INTERVAL\\s+([0-9]+)\\s+(DAY|WEEK|MONTH|YEAR)/im',
							'/CURDATE\(\)/im',
						), array(
							"DATETIME('now')",
							"DATE('now','$1$2 $3')",
							"DATE('now')",
						), $sql);
					
					$sql = preg_replace_callback('/([1-9])+\\s+WEEK/im', create_function('$matches', 'return ($matches[1] * 7) . " DAY";'), $sql);
					break;
			}
		}
	
		try
		{
			$result = parent::query($type, $sql, $as_object, $params);
		} 
		catch (Database_Exception $e)
		{
			throw new Kohana_Kohana_Exception(
				'Problem in executing query. Error was: :message', array(':message' => $e->getMessage()));
		}

		return $result;
	}

	/**
	 * 
	 * @param string $sql
	 * @return string
	 */
	private function handle_show_tables($sql)
	{
		$pattern = '/^\\s*SHOW\\s*TABLES\\s*/im';

		if (preg_match($pattern, $sql))
		{
			$sql = str_ireplace(' FULL', '', $sql);
			$table_name = '';
			$pattern = '/^\\s*SHOW\\s*TABLES\\s*.*?(LIKE\\s*(.*))$/im';
			
			if (preg_match($pattern, $sql, $matches))
			{
				$table_name = str_replace(array("'", ';'), '', $matches[2]);
			}
			
			if (!empty($table_name))
			{
				$suffix = ' AND name LIKE ' . "'" . $table_name . "'";
			}
			else
			{
				$suffix = ' AND name NOT LIKE ' . "'" . 'sqlite_sequence' . "'";
			}
	
			$sql = "SELECT name FROM sqlite_master WHERE type='table'" . $suffix . ' ORDER BY name DESC';
		}

		return $sql;
	}

	/**
	 * 
	 * @param string $sql
	 * @return string
	 */
	private function handle_show_create($sql)
	{
		$pattern = '/^\\s*SHOW\\s*CREATE\\s*TABLE\\s*/im';

		if (preg_match($pattern, $sql))
		{
			$table_name = '';
			$pattern = '/^\\s*SHOW\\s*CREATE\\s*TABLE\\s*(.+)$/im';
	
			if (preg_match($pattern, $sql, $matches))
			{
				$table_name = str_replace(array("'", ';'), '', $matches[1]);
			}
			
			if (!empty($table_name))
			{
				$suffix = ' WHERE tbl_name = ' . "'" . $table_name . "'";
			}
			else
			{
				$suffix = ' AND tbl_name NOT LIKE ' . "'" . 'sqlite_sequence' . "'";
			}
		
			$sql = "SELECT sql AS " . $table_name . " FROM sqlite_master" . $suffix;
		}
	
		return $sql;
	}
	
	private function handle_show_columns($sql)
	{
		$sql = str_ireplace(' FULL', '', $sql);
		$pattern_like = '/^\\s*SHOW\\s*(COLUMNS|FIELDS)\\s*FROM\\s*(.*)?\\s*LIKE\\s*(.*)?/i';
		$pattern = '/^\\s*SHOW\\s*(COLUMNS|FIELDS)\\s*FROM\\s*(.*)?/i';
		
		if (preg_match($pattern_like, $sql, $matches))
		{
			$table_name = str_replace("'", "", trim($matches[2]));
			$column_name = str_replace("'", "", trim($matches[3]));
			$sql = "SELECT sql FROM sqlite_master WHERE tbl_name='$table_name' AND sql LIKE '%$column_name%'";
		}
		elseif (preg_match($pattern, $sql, $matches))
		{
			$table_name = $matches[2];
			$sql = preg_replace($pattern, "PRAGMA table_info($table_name)", $sql);
		}

		return $sql;
	}

	private function handle_show_index($sql)
	{
		$pattern = '/^\\s*SHOW\\s*(?:INDEX|INDEXES|KEYS)\\s*(FROM)*\\s*(\\w+)*\\s*(WHERE)*\\s*(\\w+)?/im';

		if (preg_match($pattern, $sql, $match))
		{
			$table_name = preg_replace("/[\';]/", '', $match[3]);
			$table_name = trim($table_name);
	
			if (!empty($matches[4]))
			{
				$column_name = str_replace("'", "", trim($matches[5]));
				$suffix = " AND sql LIKE '%$column_name%'";
			}
			
			$sql = "SELECT * FROM sqlite_master WHERE tbl_name='$table_name'" . $suffix;
		}
		
		return $sql;
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