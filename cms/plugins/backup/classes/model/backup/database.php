<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Backup
 * @category	Model
 * @author		ButscHSter
 */
class Model_Backup_Database extends Model_Backup {
	
	/**
	 * 
	 * Array with the tables of the database 
	 * @var Array 
	 */
	private $tables = array();
	
	/**
	 * 
	 * The result string. String with all queries 
	 * @var String 
	 */
	private $sql;

	/** 
     * 
     * Call this function to get the database backup 
     * @example DBBackup::backup(); 
     */ 
    public function create()
	{
		DB::query(NULL, "SET CHARACTER SET cp1251")
			->execute();

        return $this
			->get_tables()
			->generate(); 
    }
	
	/**
	 * 
	 * @param string $file
	 * @return string
	 * @throws Exception
	 */
	public function view($file = NULL)
	{
		if($file === NULL)
		{
			$file = $this->file;
		}
		
		if(!file_exists($file))
		{
			throw new Exception('File '.$file.' not exists');
		}
		
		return file_get_contents($file);
	}
	
	/**
	 * 
	 * @return \Model_Backup_Database
	 */
	public function drop_tables()
	{
		foreach ($this->tables as $tbl) 
		{
			DB::query(NULL, 'DROP TABLE `:table_name`')
				->param( ':table_name', DB::expr($tbl['name']) )
				->execute();
		}
		
		return $this;
	}

	/**
	 * 
	 * @param string $file
	 * @return boolean
	 * @throws Exception
	 */
	public function restore($file = NULL)
	{
		if($file === NULL)
		{
			$file = $this->file;
		}


		if(!file_exists($file))
		{
			throw new Exception('File '.$file.' not exists');
		}

		$lines = file($file);
		$sql = '';
		
		$this->get_tables();

		DB::query(NULL, 'SET FOREIGN_KEY_CHECKS = 0')
			->execute();

		$this->drop_tables();

		if(!is_array($lines))
		{
			throw new Exception('File '.$file.' is empty');
		}
		else
		{

			foreach($lines as $line)
			{
				$sql .= trim($line);
				
				if(empty($sql))
				{
					$sql = '';
					continue;
				}
				elseif(preg_match("/^[#-].*+\r?\n?/i", trim($line)))
				{
					$sql = '';
					continue;
				}
				elseif(!preg_match("/;[\r\n]+/",$line))
				{
					continue;
				}

				try 
				{
					DB::query(Database::INSERT, $sql)
						->execute();
				}
				catch (PDOException $e)
				{
					echo($e->getMessage());
					continue;
				}

				$sql = '';
			}

			return true;
		}
	}

	/**
	 * 
	 * @param string $file
	 */
	public function save($file = NULL)
	{
		if($file === NULL)
		{
			$file = $this->file;
		}

		$handle = fopen($file , 'w+');
		fwrite($handle, $this->sql);
		fclose($handle);
	}

	/**
	 * 
	 * Generate backup string 
	 * @uses Private use 
	 * @return Model_Backup_Database
	 */
	private function generate() 
	{
		$this->sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

		foreach ($this->tables as $tbl) 
		{
			$this->sql .= "--\n";
			$this->sql .= '-- Table structure for table `' . $tbl['name'] . "`\n";
			$this->sql .= "--\n\n";
			$this->sql .= $tbl['create'] . ";\n\n";
			$this->sql .= "--\n";
			$this->sql .= '-- Dumping data for table `' . $tbl['name'] . "`\n";
			$this->sql .= "--\n\n";
			$this->sql .= $tbl['data'] . "\n\n\n";
		}

//		$this->sql .= $this->get_foreign_keys_rules();
		
		$this->sql .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";
		
		$this->sql .= "--\n\n";

		$this->sql .= "--\n";
		$this->sql .= '-- THE END' . "\n";
		$this->sql .= "--\n\n";
		
		$this->sql = mb_convert_encoding($this->sql, 'UTF-8', 'cp1251');
		
		return $this;
	}

	/**
	 * 
	 * Get the list of tables 
	 * @uses Private use 
	 * @return Model_Backup_Database
	 */
	private function get_tables() 
	{
		$tables = DB::query(Database::SELECT, 'SHOW TABLES')
			->execute();

		$this->tables = array();

		foreach ($tables as $table) 
		{
			$table = array_values($table);

			$this->tables[] = array(
				'name' => $table[0],
				'create' => $this->get_columns($table[0]),
				'data' => $this->get_data($table[0])
			);
		}

		return $this;
	}

	/**
	 * 
	 * Get the list of Columns 
	 * @uses Private use 
	 * @return string
	 */
	private function get_columns($tableName) 
	{
		$query = DB::query(Database::SELECT, 'SHOW CREATE TABLE `:table_name`')
			->param( ':table_name', DB::expr($tableName) )
			->execute()
			->current();

		if($query === NULL)
		{
			return '';
		}

		$query = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $query['Create Table']);
		return $query;
	}

	/**
	 * 
	 * Get the insert data of tables 
	 * @uses Private use 
	 * @return string
	 */
	private function get_data($tableName) 
	{
		$query = DB::query(Database::SELECT, 'SELECT * FROM `:table_name`')
			->param( ':table_name', DB::expr($tableName) )
			->execute()
			->as_array();

		$data = '';
		foreach ($query as $row) 
		{
			foreach ($row as &$value) 
			{
				$value = mysql_real_escape_string($value);
			}

			$data .= 'INSERT INTO `' . $tableName . '` VALUES (\'' . implode('\',\'', $row) . '\');' . "\n";
		}

		return $data;
	}
	
	/**
	* Gets Foreign Keys names to array
	*
	* Select CONSTRAINT_NAME from Information Schema
	*
	* @return array
	*/
	private function get_foreign_keys() 
	{
		$query = DB::select()
			->from('information_schema.TABLE_CONSTRAINTS')
			->where('CONSTRAINT_TYPE', '=', 'foreign key')
			->where('CONSTRAINT_SCHEMA', '=', DB_NAME)
			->execute()
			->as_array();

		$array = array();
		foreach ($query as $row) 
		{
			array_push($array, $row['CONSTRAINT_NAME']);
		}
		
		return $array;
	}
	
	/**
	* Return SQL command with foreign keys as string
	*
	* Function select some columns from Information Schema and write informations about foreign keys to string.
	*
	* @return string
	*/
	private function get_foreign_keys_rules()
	{
		$fk_names = $this->get_foreign_keys();
	
		$FK_to_sql_file = "";
		
		$FK_to_sql_file .= "--\n";
		$FK_to_sql_file .= '-- Foreign keys' . "\n";
		$FK_to_sql_file .= "--\n\n";

		foreach($fk_names as $fk_name)
		{

			$sql = "select KEY_COLUMN_USAGE.TABLE_NAME, KEY_COLUMN_USAGE.CONSTRAINT_NAME, COLUMN_NAME,
					REFERENCED_COLUMN_NAME, KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME, UPDATE_RULE, DELETE_RULE
					from information_schema.KEY_COLUMN_USAGE, information_schema.REFERENTIAL_CONSTRAINTS
					where KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = :db
					and KEY_COLUMN_USAGE.CONSTRAINT_NAME = :fk
					and KEY_COLUMN_USAGE.CONSTRAINT_NAME = REFERENTIAL_CONSTRAINTS.CONSTRAINT_NAME
					and KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = REFERENTIAL_CONSTRAINTS.CONSTRAINT_SCHEMA";

			$result = DB::query( Database::SELECT, $sql )
				->parameters( array(
					':db' => DB_NAME,
					':fk' => $fk_name
				))
				->execute()
				->as_array();

			foreach($result as $row)
			{
				$FK_to_sql_file 
					.= "ALTER TABLE `".$row['TABLE_NAME']
					."` ADD CONSTRAINT `".$row['CONSTRAINT_NAME']
					."` FOREIGN KEY (`".$row['COLUMN_NAME']."`) REFERENCES `"
					.$row['REFERENCED_TABLE_NAME']."` (`"
					.$row['REFERENCED_COLUMN_NAME']."`) ON DELETE {$row['DELETE_RULE']} ON UPDATE {$row['UPDATE_RULE']};";
				
				$FK_to_sql_file .= "\n";
			}
		}

		return $FK_to_sql_file;
	}
}