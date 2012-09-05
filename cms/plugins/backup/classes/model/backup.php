<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Backup {
	/**
	 * 
	 * Array with the tables of the database 
	 * @var Array 
	 */
	private $tables = array();

	public $file = NULL;

	/**
	 * 
	 * The result string. String with all queries 
	 * @var String 
	 */
	private $sql;

	public function __construct($file = NULL) 
	{
		$this->file = $file;
	}
	
	public static function factory($file = NULL)
	{
		return new self($file);
	}


	/** 
     * 
     * Call this function to get the database backup 
     * @example DBBackup::backup(); 
     */ 
    public function create()
	{
		DB::query(Database::INSERT, "SET CHARACTER SET cp1251")
			->execute();

        return $this
			->get_tables()
			->generate(); 
    }
	
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

		foreach ($this->tables as $tbl) 
		{
			DB::query(Database::DELETE, 'DROP TABLE `:table_name`')
				->param( ':table_name', DB::expr($tbl['name']) )
				->execute();
		}

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
	 */
	private function generate() 
	{
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
				$value = mysql_escape_string($value);
			}

			$data .= 'INSERT INTO `' . $tableName . '` VALUES (\'' . implode('\',\'', $row) . '\');' . "\n";
		}

		return $data;
	}
}