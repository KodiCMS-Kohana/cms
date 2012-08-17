<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * The Record class represents a database record.
 * 
 * It is used as an abstraction layer so classes don't need to implement their own
 * database functionality.
 */
class Record
{
    const PARAM_BOOL = 5;
    const PARAM_NULL = 0;
    const PARAM_INT = 1;
    const PARAM_STR = 2;
    const PARAM_LOB = 3;
    const PARAM_STMT = 4;
    const PARAM_INPUT_OUTPUT = -2147483648;
    const PARAM_EVT_ALLOC = 0;
    const PARAM_EVT_FREE = 1;
    const PARAM_EVT_EXEC_PRE = 2;
    const PARAM_EVT_EXEC_POST = 3;
    const PARAM_EVT_FETCH_PRE = 4;
    const PARAM_EVT_FETCH_POST = 5;
    const PARAM_EVT_NORMALIZE = 6;
    
    const FETCH_LAZY = 1;
    const FETCH_ASSOC = 2;
    const FETCH_NUM = 3;
    const FETCH_BOTH = 4;
    const FETCH_OBJ = 5;
    const FETCH_BOUND = 6;
    const FETCH_COLUMN = 7;
    const FETCH_CLASS = 8;
    const FETCH_INTO = 9;
    const FETCH_FUNC = 10;
    const FETCH_GROUP = 65536;
    const FETCH_UNIQUE = 196608;
    const FETCH_CLASSTYPE = 262144;
    const FETCH_SERIALIZE = 524288;
    const FETCH_PROPS_LATE = 1048576;
    const FETCH_NAMED = 11;
    
    const ATTR_AUTOCOMMIT = 0;
    const ATTR_PREFETCH = 1;
    const ATTR_TIMEOUT = 2;
    const ATTR_ERRMODE = 3;
    const ATTR_SERVER_VERSION = 4;
    const ATTR_CLIENT_VERSION = 5;
    const ATTR_SERVER_INFO = 6;
    const ATTR_CONNECTION_STATUS = 7;
    const ATTR_CASE = 8;
    const ATTR_CURSOR_NAME = 9;
    const ATTR_CURSOR = 10;
    const ATTR_ORACLE_NULLS = 11;
    const ATTR_PERSISTENT = 12;
    const ATTR_STATEMENT_CLASS = 13;
    const ATTR_FETCH_TABLE_NAMES = 14;
    const ATTR_FETCH_CATALOG_NAMES = 15;
    const ATTR_DRIVER_NAME = 16;
    const ATTR_STRINGIFY_FETCHES = 17;
    const ATTR_MAX_COLUMN_LEN = 18;
    const ATTR_EMULATE_PREPARES = 20;
    const ATTR_DEFAULT_FETCH_MODE = 19;
    
    const ERRMODE_SILENT = 0;
    const ERRMODE_WARNING = 1;
    const ERRMODE_EXCEPTION = 2;
    const CASE_NATURAL = 0;
    const CASE_LOWER = 2;
    const CASE_UPPER = 1;
    const NULL_NATURAL = 0;
    const NULL_EMPTY_STRING = 1;
    const NULL_TO_STRING = 2;
    const ERR_NONE = '00000';
    const FETCH_ORI_NEXT = 0;
    const FETCH_ORI_PRIOR = 1;
    const FETCH_ORI_FIRST = 2;
    const FETCH_ORI_LAST = 3;
    const FETCH_ORI_ABS = 4;
    const FETCH_ORI_REL = 5;
    const CURSOR_FWDONLY = 0;
    const CURSOR_SCROLL = 1;
    const MYSQL_ATTR_USE_BUFFERED_QUERY = 1000;
    const MYSQL_ATTR_LOCAL_INFILE = 1001;
    const MYSQL_ATTR_INIT_COMMAND = 1002;
    const MYSQL_ATTR_READ_DEFAULT_FILE = 1003;
    const MYSQL_ATTR_READ_DEFAULT_GROUP = 1004;
    const MYSQL_ATTR_MAX_BUFFER_SIZE = 1005;
    const MYSQL_ATTR_DIRECT_QUERY = 1006;
    
    public static $__CONN__ = false;
    public static $__QUERIES__ = array();
    
    final public static function connection($connection)
    {
        self::$__CONN__ = $connection;
    }
    
    final public static function getConnection()
    {
        return self::$__CONN__;
    }
    
    final public static function logQuery($sql)
    {
        self::$__QUERIES__[] = $sql;
    }
    
    final public static function getQueryLog()
    {
        return self::$__QUERIES__;
    }
    
    final public static function getQueryCount()
    {
        return count(self::$__QUERIES__);
    }
    
    final public static function query($sql, $values=false)
    {
        self::logQuery($sql);
        
        if (is_array($values))
		{
            $stmt = self::$__CONN__->prepare($sql);
            $stmt->execute($values);
            return $stmt->fetchAll(self::FETCH_OBJ);
        }
		else
		{
            return self::$__CONN__->query($sql);
        }
    }
    
    final public static function tableNameFromClassName($class_name)
    {
        try
        {
            if (class_exists($class_name) && defined($class_name.'::TABLE_NAME'))
                return TABLE_PREFIX.constant($class_name.'::TABLE_NAME');
        }
        catch (Exception $e)
        {
            return TABLE_PREFIX.Inflector::underscore($class_name);
        }
    }
    
    final public static function escape($value)
    {
        return self::$__CONN__->quote($value);
    }
    
    final public static function lastInsertId()
    {
        return self::$__CONN__->lastInsertId();
    }
    
    public function __construct($data=false, $exclude = array())
    {
        if (is_array($data))
            $this->setFromData($data, $exclude);
    }
    
    public function setFromData($data, $exclude = array())
    {
        foreach($data as $key => $value)
		{
			if(!in_array($key, $exclude))
			{
				$this->$key = $value;
			}
        }
    }
    
    /**
     * Generates an insert or update string from the supplied data and executes it
     *
     * @return boolean
     */
    public function save()
    {
        if ( ! $this->beforeSave()) return false;
        
        $value_of = array();

        if( empty($this->id) )
		{            
            if ( ! $this->beforeInsert()) return false;
            
            $columns = $this->getColumns();
            
            // Escape and format for SQL insert query
            foreach ($columns as $column)
			{
                if (isset($this->$column))
				{
                    $value_of[$column] = self::$__CONN__->quote($this->$column);
                }
            }
            
            $sql = 'INSERT INTO '.self::tableNameFromClassName(get_class($this)).' ('
                 . implode(', ', array_keys($value_of)).') VALUES ('.implode(', ', array_values($value_of)).')';
			
            $return = self::$__CONN__->exec($sql) !== false;
            $this->id = self::lastInsertId(); 
             
            if ( ! $this->afterInsert()) return false;
        
        }
		else
		{
            if ( ! $this->beforeUpdate()) return false;
            
            $columns = $this->getColumns();
            
            // Escape and format for SQL update query
            foreach( $columns as $column )
			{
                if( isset($this->$column) )
				{
                    $value_of[$column] = $column.'='.self::$__CONN__->quote($this->$column);
                }
            }
            
            unset($value_of['id']);
            
            $sql = 'UPDATE '.self::tableNameFromClassName(get_class($this)).' SET '
                 . implode(', ', $value_of).' WHERE id = '.$this->id;
			
            $return = self::$__CONN__->exec($sql) !== false;
            
            if( ! $this->afterUpdate() ) return false;
        }
        
        self::logQuery($sql);
        
        // Run it !!...
        return $return;
    }

    /**
     * Generates a delete string and executes it
     *
     * @param string $table the table name
     * @param string $where the query condition
     * @return boolean
     */
    public function delete()
    {
        if ( ! $this->beforeDelete()) return false;
        $sql = 'DELETE FROM '.self::tableNameFromClassName(get_class($this))
             . ' WHERE id='.self::$__CONN__->quote($this->id);

        // Run it !!...
        $return = self::$__CONN__->exec($sql) !== false;
        if ( ! $this->afterDelete()) {
            $this->save();
            return false;
        }
        
        self::logQuery($sql);
        
        return $return;
    }
    
    public function beforeSave() { return true; }
    public function beforeInsert() { return true; }
    public function beforeUpdate() { return true; }
    public function beforeDelete() { return true; }
    public function afterSave() { return true; }
    public function afterInsert() { return true; }
    public function afterUpdate() { return true; }
    public function afterDelete() { return true; }
    
    /**
     * Return an array of all columns in the table
     * It is a good idea to rewrite this method in all your model classes;
     * used in save() for creating the insert and/or update sql query
     */
    public function getColumns()
    {
        return array_keys(get_object_vars($this));
    }
    
    public static function insert($class_name, $data)
    {
        $keys = array();
        $values = array();
        
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = self::$__CONN__->quote($value);
        }
        
        $sql = 'INSERT INTO '.self::tableNameFromClassName($class_name).' ('.join(', ', $keys).') VALUES ('.join(', ', $values).')';
        
        self::logQuery($sql);
        
        // Run it !!...
        return self::$__CONN__->exec($sql) !== false;
    }
    
    public static function update($class_name, $data, $where, $values=array())
    {
        $setters = array();
        
        // Prepare request by binding keys
        foreach ($data as $key => $value) {
            $setters[] = $key.'='.self::$__CONN__->quote($value);
        }
        
        $sql = 'UPDATE '.self::tableNameFromClassName($class_name).' SET '.join(', ', $setters).' WHERE '.$where;
        
        self::logQuery($sql);
        
        $stmt = self::$__CONN__->prepare($sql);
        return $stmt->execute($values);
    }
    
    public static function deleteWhere($class_name, $where, $values=array())
    {
        $sql = 'DELETE FROM '.self::tableNameFromClassName($class_name).' WHERE '.$where;
        
        self::logQuery($sql);
        
        $stmt = self::$__CONN__->prepare($sql);
        return $stmt->execute($values);
    }
    
    //
    // Note: lazy finder or getter method. Pratical when you need something really 
    //       simple no join or anything will only generate simple select * from table ...
    //
    
    public static function findByIdFrom($class_name, $id)
    {
        return self::findOneFrom($class_name, 'id=?', array($id));
    }
    
    public static function findOneFrom($class_name, $where, $values=array())
    {
        $sql = 'SELECT * FROM '.self::tableNameFromClassName($class_name).' WHERE '.$where;

        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute($values);
        
        self::logQuery($sql);
        
        return $stmt->fetchObject($class_name);
    }
    
    public static function findAllFrom($class_name, $where=false, $values=array())
    {
        $sql = 'SELECT * FROM '.self::tableNameFromClassName($class_name).($where ? ' WHERE '.$where:'');
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute($values);
        
        self::logQuery($sql);
        
        $objects = array();
        while ($object = $stmt->fetchObject($class_name))
            $objects[] = $object;
        
        return $objects;
    }
    
    public static function countFrom($class_name, $where=false, $values=array())
    {
        $sql = 'SELECT COUNT(*) AS nb_rows FROM '.self::tableNameFromClassName($class_name).($where ? ' WHERE '.$where:'');
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute($values);
        
        self::logQuery($sql);
        
        return (int) $stmt->fetchColumn();
    }

}