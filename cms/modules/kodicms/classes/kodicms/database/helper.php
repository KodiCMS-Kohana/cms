<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Helper
 * @author		ButscHSter
 */
class KodiCMS_Database_Helper {
	
	/**
	 * @param string $data
	 * @throws Validation_Exception
	 */
	public static function insert_sql( $sql_data, $db = NULL )
	{
		$sql_data = str_replace('__TABLE_PREFIX__', TABLE_PREFIX, $sql_data);
		$sql_data = preg_split( '/;(\s*)$/m', $sql_data );

		DB::query(NULL, 'SET FOREIGN_KEY_CHECKS = 0')
			->execute($db);

		foreach($sql_data as $sql)
		{
			if(empty($sql))
			{
				continue;
			}

			DB::query(Database::INSERT, $sql)
				->execute($db);
		}

		DB::query(NULL, 'SET FOREIGN_KEY_CHECKS = 1')
			->execute($db);
	}
	
	/**
	 * 
	 * @param Database $db
	 * @return string
	 */
	public static function schema( $db = NULL )
	{
		$tables = DB::query(Database::SELECT, 'SHOW TABLES')->execute($db)->as_array();

		$schema = '';

		foreach ($tables as $key => $table) 
		{
			$table = array_values($table);
			$table_name = $table[0];
			
			$schema .= DB::query(Database::SELECT, 'SHOW CREATE TABLE `:table_name`')
				->param( ':table_name', DB::expr($table_name) )
				->execute($db)->get('Create Table');
			
			$schema .= ";\n\n";
		}
		
		return $schema;
	}
	
	/**
	 * Получение схемы БД из SQL файлов модулей и активированных плагинов
	 * 
	 * @return string
	 */
	public static function install_schema()
	{
		$schema = '';

		// Create a new directory iterator
		$path = new DirectoryIterator(MODPATH);

		foreach ($path as $dir)
		{
			if($dir->isDot()) continue;
			$file_name = MODPATH . $dir->getBasename() . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'schema.sql';
			if(file_exists($file_name))
			{
				$schema .= file_get_contents( $file_name );
				$schema .= "\n\n";
			}
		}
		
		if(class_exists('Plugins'))
		{
			foreach (Plugins::activated() as $id)
			{
				$file_name = PLUGPATH . $id . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'schema.sql';
				
				if(file_exists($file_name))
				{
					$schema .= file_get_contents( $file_name );
					$schema .= "\n\n";
				}
			}
		}
		
		return str_replace('__TABLE_PREFIX__', TABLE_PREFIX, $schema);
	}

	/**
	 * structure dump of the reference database
	 * @var string 
	 */
	protected $_source_struct = '';
	
	/**
	 * structure dump of database to update
	 * @var string
	 */
	protected $_dest_struct = '';
	
	/**
	 * updater _configuration
	 * @var array 
	 */
	protected $__config = array();

	/**
	* Constructor
	* @access public
	*/
	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
		//table operations: create, drop; field operations: add, remove, modify
		$this->_config['updateTypes'] = 'create, drop, add, remove, modify';
		
		//ignores default part in cases like (var)char NOT NULL default '' upon the	comparison
		$this->_config['varcharDefaultIgnore'] = TRUE;
		
		//the same for int NOT NULL default 0
		$this->_config['intDefaultIgnore'] = TRUE;
		
		//ignores table autoincrement field value, also remove AUTO_INCREMENT value from the create query if exists
		$this->_config['ignoreIncrement'] = TRUE;
		
		//add 'IF NOT EXIST' to each CREATE TABLE query
		$this->_config['forceIfNotExists'] = TRUE;
		
		//remove 'IF NOT EXIST' if already exists CREATE TABLE dump
		$this->_config['ingoreIfNotExists'] = FALSE;
	}

	/**
	* merges current updater _config with the given one
	* @param assoc_array $_config new configuration values
	*/
	function _set_config($_config=array())
	{
		if (is_array($_config))
		{
			$this->_config = array_merge($this->_config, $_config);
		}
	}

	/**
	* Returns array of update SQL with default options, $source, $dest - database structures
	* @access public
	* @param string $source structure dump of database to update
	* @param string $dest structure dump of the reference database
	* @param bool $asString if TRUE - result will be a string, otherwise - array
	* @return array|string update sql statements - in array or string (separated with ';')
	*/
	public function get_updates($source, $dest, $asString=FALSE)
	{
		$result = $asString?'':array();
		$compRes = $this->compare($source, $dest);
		if (empty($compRes))
		{
			return $result;
		}
		$compRes = $this->_filter_diffs($compRes);
		if (empty($compRes))
		{
			return $result;
		}
		$result = $this->_get_diff_sql($compRes);
		if ($asString)
		{
			$result = implode(";\r\n\n", $result).';';
		}
		return $result;
	}

	/**
	* Filters comparison result and lefts only sync actions allowed by 'updateTypes' option
	*/
	protected function _filter_diffs($compRes)
	{
		$result = array();
		if (is_array($this->_config['updateTypes']))
		{
			$updateActions = $this->_config['updateTypes'];
		}
		else
		{
			$updateActions = array_map('trim', explode(',', $this->_config['updateTypes']));
		}
		$allowedActions = array('create', 'drop', 'add', 'remove', 'modify');
		$updateActions = array_intersect($updateActions, $allowedActions);
		foreach($compRes as $table=>$info)
		{
			if ($info['sourceOrphan'])
			{
				if (in_array('create', $updateActions))
				{
					$result[$table] = $info;
				}
			}
			elseif ($info['destOrphan'])
			{
				if (in_array('drop', $updateActions))
				{
					$result[$table] = $info;
				}
			}
			elseif($info['differs'])
			{
				$resultInfo = $info;
				unset($resultInfo['differs']);
				foreach ($info['differs'] as $diff)
				{
					if (empty($diff['dest']) && in_array('add', $updateActions))
					{
						$resultInfo['differs'][] = $diff;
					}
					elseif (empty($diff['source']) && in_array('remove', $updateActions))
					{
						$resultInfo['differs'][] = $diff;
					}
					elseif(in_array('modify', $updateActions))
					{
						$resultInfo['differs'][] = $diff;
					}
				}
				if (!empty($resultInfo['differs']))
				{
					$result[$table] = $resultInfo;
				}
			}
		}
		return $result;
	}
	
	/**
	* Gets structured general info about the databases diff :
	* array(sourceOrphans=>array(...), destOrphans=>array(...), different=>array(...))
	*/
	public function get_diff_info($compRes)
	{		
		if (!is_array($compRes))
		{
			return FALSE;
		}

		$result = array('sourceOrphans'=>array(), 'destOrphans'=>array(), 'different'=>array());
		foreach($compRes as $table=>$info)
		{
			if ($info['sourceOrphan'])
			{
				$result['sourceOrphans'][] = $table;
			}
			elseif ($info['destOrphan'])
			{
				$result['destOrphans'][] = $table;
			}
			else
			{
				$result['different'][] = $table;
			}
		}
		return $result;
	}

	/**
	* Makes comparison of the given database structures, support some options
	* @access private
	* @param string $source and $dest are strings - database tables structures
	* @return array
	* - table (array)
	*		- destOrphan (boolean)
	*		- sourceOrphan (boolean)
	*		- differs (array) OR (boolean) FALSE if no diffs
	*			- [0](array)
	*				- source (string) structure definition line in the out-of-date table
	*				- dest (string) structure definition line in the reference table
	*			- [1](array) ...
	*/
	public function compare($source, $dest)
	{
		$this->_source_struct = $source;
		$this->_dest_struct = $dest;

		$result = array();
		$destTabNames = $this->_get_table_list($this->_dest_struct);
		$sourceTabNames = $this->_get_table_list($this->_source_struct);

		$common = array_intersect($destTabNames, $sourceTabNames);
		$destOrphans = array_diff($destTabNames, $common);
		$sourceOrphans = array_diff($sourceTabNames, $common);
		
		$all = array_unique(array_merge($destTabNames, $sourceTabNames));
		sort($all);
		foreach ($all as $tab)
		{
			$info = array('destOrphan'=>FALSE, 'sourceOrphan'=>FALSE, 'differs'=>FALSE);
			if(in_array($tab, $destOrphans))
			{
				$info['destOrphan'] = TRUE;
			}
			elseif (in_array($tab, $sourceOrphans))
			{
				$info['sourceOrphan'] = TRUE;
			}
			else
			{				
				$destSql = $this->_get_tab_sql($this->_dest_struct, $tab, TRUE);
				$sourceSql = $this->_get_tab_sql($this->_source_struct, $tab, TRUE);

				$diffs = $this->_compare_sql($sourceSql, $destSql);				
				if ($diffs===FALSE)
				{
					trigger_error('[WARNING] error parsing definition of table "'.$tab.'" - skipped');
					continue;
				}
				elseif (!empty($diffs))//not empty array
				{
					$info['differs'] = $diffs;					
				}				
				else continue;//empty array
			}
			$result[$tab] = $info;
		}
		return $result;
	}

	/**
	* Retrieves list of table names from the database structure dump
	* @access private
	* @param string $struct database structure listing
	*/
	protected function _get_table_list($struct)
	{
		$result = array();
		if (preg_match_all('/CREATE(?:\s*TEMPORARY)?\s*TABLE\s*(?:IF NOT EXISTS\s*)?(?:`?(\w+)`?\.)?`?(\w+)`?/i', $struct, $m))
		{
			foreach($m[2] as $match)//m[1] is a database name if any
			{
				$result[] = $match;
			}
		}
		return $result;
	}

	/**
	* Retrieves table structure definition from the database structure dump
	* @access private
	* @param string $struct database structure listing
	* @param string $tab table name
	* @param bool $removeDatabase - either to remove database name in "CREATE TABLE database.tab"-like declarations
	* @return string table structure definition
	*/
	protected function _get_tab_sql($struct, $tab, $removeDatabase = TRUE)
	{
		$result = '';
		/* create table should be single line in this case*/
		//1 - part before database, 2-database name, 3 - part after database
		if (preg_match('/(CREATE(?:\s*TEMPORARY)?\s*TABLE\s*(?:IF NOT EXISTS\s*)?)(?:`?(\w+)`?\.)?(`?('.$tab.')`?(\W|$))/i', $struct, $m, PREG_OFFSET_CAPTURE))		
		{
			$tableDef = $m[0][0];
			$start = $m[0][1];
			$database = $m[2][0];
			$offset = $start+strlen($m[0][0]);
			$end = $this->_get_delim_pos($struct, $offset);
			if ($end === FALSE)
			{
				$result = substr($struct, $start);
			}
			else
			{
				$result = substr($struct, $start, $end-$start);//already without ';'
			}
		}
		$result = trim($result);
		if ($database && $removeDatabase)
		{
			$result = str_replace($tableDef, $m[1][0].$m[3][0], $result);
		}
		return $result;
	}
	
	/**
	* Splits table sql into indexed array
	* 
	*/
	protected function _split_tab_sql($sql)
	{
		$result = array();
		//find opening bracket, get the prefix along with it
		$openBracketPos = $this->_get_delim_pos($sql, 0, '(');
		if ($openBracketPos===FALSE)
		{
			trigger_error('[WARNING] can not find opening bracket in table definition');
			return FALSE;
		}
		$prefix = substr($sql, 0, $openBracketPos+1);//prefix can not be empty, so do not check it, just trim
		$result[] = trim($prefix);
		$body = substr($sql, strlen($prefix));//fields, indexes and part after closing bracket
		//split by commas, get part by part
		while(($commaPos = $this->_get_delim_pos($body, 0, ',', TRUE))!==FALSE)
		{
			$part = trim(substr($body, 0, $commaPos+1));//read another part and shorten $body
			if ($part)
			{
				$result[] = $part;
			}
			$body = substr($body, $commaPos+1);
		}
		//here we have last field (or index) definition + part after closing bracket (ENGINE, ect)
		$closeBracketPos = $this->_get_delim_rpos($body, 0, ')');
		if ($closeBracketPos===FALSE)
		{
			trigger_error('[WARNING] can not find closing bracket in table definition');
			return FALSE;
		}
		//get last field / index definition before closing bracket
		$part = substr($body, 0, $closeBracketPos);
		$result[] = trim($part);
		//get the suffix part along with the closing bracket
		$suffix = substr($body, $closeBracketPos);
		$suffix = trim($suffix);
		if ($suffix)
		{
			$result[] = $suffix;
		}
		return $result;
	}

	/**
	* returns array of fields or keys definitions that differs in the given tables structure
	* @access private
	* @param sring $sourceSql table structure
	* @param sring $destSql right table structure
	* supports some $options
	* @return array
	* 	- [0]
	* 		- source (string) out-of-date table field definition
	* 		- dest (string) reference table field definition
	* 	- [1]...
	*/
	protected function _compare_sql($sourceSql, $destSql)//$sourceSql, $destSql
	{
		$result = array();		
		//split with comma delimiter, not line breaks
		$sourceParts =  $this->_split_tab_sql($sourceSql);
		if ($sourceParts===FALSE)//error parsing sql
		{
			trigger_error('[WARNING] error parsing source sql');
			return FALSE;
		}
		$destParts = $this->_split_tab_sql($destSql);
		if ($destParts===FALSE)
		{
			trigger_error('[WARNING] error parsing destination sql');
			return FALSE;
		}
		
		$sourcePartsIndexed = array();
		$destPartsIndexed = array();
		foreach($sourceParts as $line)
		{			
			$lineInfo = $this->_process_line($line);
			if (!$lineInfo) continue;
			$sourcePartsIndexed[$lineInfo['key']] = $lineInfo['line'];
		}
		foreach($destParts as $line)
		{			
			$lineInfo = $this->_process_line($line);
			if (!$lineInfo) continue;
			$destPartsIndexed[$lineInfo['key']] = $lineInfo['line'];
		}
		$sourceKeys = array_keys($sourcePartsIndexed);
		$destKeys = array_keys($destPartsIndexed);
		$all = array_unique(array_merge($sourceKeys, $destKeys));
		sort($all);//fields first, then indexes - because fields are prefixed with '!'
		
		foreach ($all as $key)
		{
			
			$info = array('source'=>'', 'dest'=>'');
			$inSource= in_array($key, $sourceKeys);
			$inDest= in_array($key, $destKeys);
			$sourceOrphan = $inSource && !$inDest;
			$destOrphan = $inDest && !$inSource;
			$different =  $inSource && $inDest && 
			strcasecmp($this->_normalize_string($destPartsIndexed[$key]), $this->_normalize_string($sourcePartsIndexed[$key]));
			if ($sourceOrphan)
			{
				$info['source'] = $sourcePartsIndexed[$key];
			}
			elseif ($destOrphan)
			{
				$info['dest'] = $destPartsIndexed[$key];
			}
			elseif ($different)
			{
				$info['source'] = $sourcePartsIndexed[$key];
				$info['dest'] = $destPartsIndexed[$key];
			}
			else continue;
			$result[] = $info;
		}
		return $result;
	}

	/**
	* Transforms table structure defnition line into key=>value pair where the key is a string that uniquely
	* defines field or key desribed
	* @access private
	* @param string $line field definition string
	* @return array array with single key=>value pair as described in the description
	* implements some options
	*/
	protected function _process_line($line)
	{
		$options = $this->_config;
		$result = array('key'=>'', 'line'=>'');
		$line = rtrim(trim($line), ',');
		if (preg_match('/^(CREATE\s+TABLE)|(\) ENGINE=)/i', $line))//first or last table definition line
		{
			return FALSE;
		}
		//if (preg_match('/^(PRIMARY KEY)|(((UNIQUE )|(FULLTEXT ))?KEY `?\w+`?)/i', $line, $m))//key definition
		if (preg_match('/^(PRIMARY\s+KEY)|(((UNIQUE\s+)|(FULLTEXT\s+))?KEY\s+`?\w+`?)/i', $line, $m))//key definition
		{
			$key = $m[0];
		}
		elseif (preg_match('/^`?\w+`?/i', $line, $m))//field definition
		{
			$key = '!'.$m[0];//to make sure fields will be synchronised before the keys
		}
		else
		{
			return FALSE;//line has no valuable info (empty or comment)
		}
		//$key = str_replace('`', '', $key);
		if (!empty($options['varcharDefaultIgnore']))
		{
			$line = preg_replace("/(var)?char\(([0-9]+)\)\s+NOT\s+NULL\s+default\s+''/i", '$1char($2) NOT NULL', $line);
		}
		if (!empty($options['intDefaultIgnore']))
		{
			$line = preg_replace("/((?:big)|(?:tiny))?int\(([0-9]+)\)\s+NOT\s+NULL\s+default\s+'0'/i", '$1int($2) NOT NULL', $line);
		}
		if (!empty($options['ignoreIncrement']))
		{
			$line = preg_replace("/ AUTO_INCREMENT=[0-9]+/i", '', $line);
		}
		$result['key'] = $this->_normalize_string($key);
		$result['line']= $line;
		return $result;
	}

	/**
	* Takes an output of compare() method to generate the set of sql needed to update source table to make it
	* look as a destination one
	* @access private
	* @param array $diff compare() method output
	* @return array list of sql statements
	* supports query generation options
	*/
	protected function _get_diff_sql($diff)//maybe add option to ommit or force 'IF NOT EXISTS', skip autoincrement
	{
		$options = $this->_config;
		$sqls = array();
		if (!is_array($diff) || empty($diff))
		{
			return $sqls;
		}
		foreach($diff as $tab=>$info)
		{
			if ($info['sourceOrphan'])//delete it
			{
				$sqls[] = "-- DROP TABLE `{$tab}`";
			}
			elseif ($info['destOrphan'])//create destination table in source
			{
				$database = '';
				$destSql = $this->_get_tab_sql($this->_dest_struct, $tab, $database);
				if (!empty($options['ignoreIncrement']))
				{
					$destSql = preg_replace("/\s*AUTO_INCREMENT=[0-9]+/i", '', $destSql);
				}
				if (!empty($options['ingoreIfNotExists']))
				{
					$destSql = preg_replace("/IF NOT EXISTS\s*/i", '', $destSql);
				}
				if (!empty($options['forceIfNotExists']))
				{
					$destSql = preg_replace('/(CREATE(?:\s*TEMPORARY)?\s*TABLE\s*)(?:IF\sNOT\sEXISTS\s*)?(`?\w+`?)/i', '$1IF NOT EXISTS $2', $destSql);
				}
				$sqls[] = $destSql;
			}
			else
			{
				foreach($info['differs'] as $finfo)
				{
					$inDest = !empty($finfo['dest']);
					$inSource = !empty($finfo['source']);
					if ($inSource && !$inDest)
					{
						$sql = $finfo['source'];
						$action = 'drop';
					}
					elseif ($inDest && !$inSource)
					{
						$sql = $finfo['dest'];
						$action = 'add';
					}
					else
					{
						$sql = $finfo['dest'];
						$action = 'modify';
					}
					$sql = $this->_get_action_sql($action, $tab, $sql);
					$sqls[] = $sql;
				}
			}
		}
		
		rsort($sqls);
		return $sqls;
	}

	/**
	* Compiles update sql
	* @access private
	* @param string $action - 'drop', 'add' or 'modify'
	* @param string $tab table name
	* @param string $sql definition of the element to change
	* @return string update sql
	*/
	protected function _get_action_sql($action, $tab, $sql)
	{
		$result = 'ALTER TABLE `'.$tab.'` ';
		$action = strtolower($action);
		$keyField = '`?\w`?(?:\(\d+\))?';//matches `name`(10)
		$keyFieldList = '(?:'.$keyField.'(?:,\s?)?)+';//matches `name`(10),`desc`(255)
		if (preg_match('/((?:PRIMARY )|(?:UNIQUE )|(?:FULLTEXT ))?KEY `?(\w+)?`?\s(\('.$keyFieldList.'\))/i', $sql, $m))
		{   //key and index operations
			$type = strtolower(trim($m[1]));
			$name = trim($m[2]);
			$fields = trim($m[3]);
			switch($action)
			{
				case 'drop':
					if ($type=='primary')
					{
						$result.= 'DROP PRIMARY KEY';
					}
					else
					{
						$result.= 'DROP INDEX `'.$name.'`';
					}
				break;
				case 'add':
					if ($type=='primary')
					{
						$result.= 'ADD PRIMARY KEY '.$fields;
					}
					elseif ($type=='')
					{
						$result.= 'ADD INDEX `'.$name.'` '.$fields;
					}
					else
					{
						$result .='ADD '.strtoupper($type).' `'.$name.'` '.$fields;//fulltext or unique
					}
				break;
				case 'modify':
					if ($type=='primary')
					{
						$result.='DROP PRIMARY KEY, ADD PRIMARY KEY '.$fields;
					}
					elseif ($type=='')
					{
						$result.='DROP INDEX `'.$name.'`, ADD INDEX `'.$name.'` '.$fields;
					}
					else
					{
						$result.='DROP INDEX `'.$name.'`, ADD '.strtoupper($type).' `'.$name.'` '.$fields;//fulltext or unique
					}
				break;

			}
		}
		else //fields operations
		{
			$sql = rtrim(trim($sql), ',');
			$result.= strtoupper($action);
			if ($action=='drop')
			{
				$spacePos = strpos($sql, ' ');
				$result.= ' '.substr($sql, 0, $spacePos);
			}
			else
			{
				$result.= ' '.$sql;
			}
		}
		return $result;
	}

	/**
	* Searches for the position of the next delimiter which is not inside string literal like 'this ; ' or
	* like "this ; ".
	*
	* Handles escaped \" and \'. Also handles sql comments.
	* Actualy it is regex-based Finit State Machine (FSN)
	*/
	protected function _get_delim_pos($string, $offset=0, $delim=';', $skipInBrackets=FALSE)
	{
		$stack = array();
		$rbs = '\\\\';	//reg - escaped backslash
		$regPrefix = "(?<!$rbs)(?:$rbs{2})*";
		$reg = $regPrefix.'("|\')|(/\\*)|(\\*/)|(-- )|(\r\n|\r|\n)|';
		if ($skipInBrackets) 
		{
			$reg.='(\(|\))|';
		}
		else 
		{
			$reg.='()';
		}
		$reg .= '('.preg_quote($delim).')';
		while (preg_match('%'.$reg.'%', $string, $m, PREG_OFFSET_CAPTURE, $offset))
		{
			$offset = $m[0][1]+strlen($m[0][0]);
			if (end($stack)=='/*')
			{
				if (!empty($m[3][0]))
				{
					array_pop($stack);
				}
				continue;//here we could also simplify regexp
			}
			if (end($stack)=='-- ')
			{
				if (!empty($m[5][0]))
				{
					array_pop($stack);
				}
				continue;//here we could also simplify regexp
			}

			if (!empty($m[7][0]))// ';' found
			{
				if (empty($stack))
				{
					return $m[7][1];
				}
				else
				{
					//var_dump($stack, substr($string, $offset-strlen($m[0][0])));
				}
			}
			if (!empty($m[6][0]))// '(' or ')' found
			{
				if (empty($stack) && $m[6][0]=='(')
				{
					array_push($stack, $m[6][0]);
				}
				elseif($m[6][0]==')' && end($stack)=='(')
				{
					array_pop($stack);
				}
			}
			elseif (!empty($m[1][0]))// ' or " found
			{
				if (end($stack)==$m[1][0])
				{
					array_pop($stack);
				}
				else
				{
					array_push($stack, $m[1][0]);
				}
			}
			elseif (!empty($m[2][0])) // opening comment / *
			{
				array_push($stack, $m[2][0]);
			}
			elseif (!empty($m[4][0])) // opening comment --
			{
				array_push($stack, $m[4][0]);
			}
		}
		return FALSE;
	}
	
	/**
	* works the same as _get_delim_pos except returns position of the first occurence of the delimiter starting from
	* the end of the string
	*/
	protected function _get_delim_rpos($string, $offset=0, $delim=';', $skipInBrackets=FALSE)
	{
		$pos = $this->_get_delim_pos($string, $offset, $delim, $skipInBrackets);
		if ($pos===FALSE)
		{
			return FALSE;
		}
		do
		{
			$newPos=$this->_get_delim_pos($string, $pos+1, $delim, $skipInBrackets);
			if ($newPos !== FALSE)
			{
				$pos = $newPos;
			}
		}
		while($newPos!==FALSE);
		return $pos;
	}

	/**
	 * Converts string to lowercase and replaces repeated spaces with the single one -
	 * to be used for the comparison purposes only
	 * @param string $str string to normaize
	 */
	protected function _normalize_string($str)
	{
		$str = strtolower($str);
		$str = preg_replace('/\s+/', ' ', $str);
		return $str;
	}
}