<?php defined('SYSPATH') or die('No direct access allowed.');

class Record
{
    final public static function tableName($class_name = NULL)
    {
		if($class_name === NULL)
		{
			$class_name = get_called_class();
		}

        try
        {
            if (class_exists($class_name) && defined($class_name.'::TABLE_NAME'))
			{
                return TABLE_PREFIX.constant($class_name.'::TABLE_NAME');
			}
        }
        catch (Exception $e)
        {
            return TABLE_PREFIX.Inflector::underscore($class_name);
        }
    }
    
    public function __construct($data=FALSE, $exclude = array())
    {
        if (is_array($data))
		{
            $this->setFromData($data, $exclude);
		}
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
        if ( ! $this->beforeSave()) return FALSE;

        if( !isset($this->id ))
		{
			
            if ( ! $this->beforeInsert()) return FALSE;

			$data = $this->_prepare_data();

			$return = self::insert(NULL, $data);

            $this->id = $return[0]; 
             
            if ( ! $this->afterInsert()) return FALSE;
        }
		else
		{
            if ( ! $this->beforeUpdate()) return FALSE;
            
			$data = $this->_prepare_data(array('id'));

			self::update(NULL, $data, 'id = :id', array(':id' => $this->id));
			
			$return = TRUE;
            
            if( ! $this->afterUpdate() ) return FALSE;
        }
        
        // Run it !!...
        return $return;
    }
	
	protected function _prepare_data($exclude = array())
	{
		$data = array();
		$columns = $this->getColumns();
            
		// Escape and format for SQL insert query
		foreach ($columns as $column)
		{
			if (isset($this->$column) AND !in_array( $column, $exclude ))
			{
				$data[$column] = $this->$column;
			}
		}
		
		return $data;
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
        if ( ! $this->beforeDelete()) return FALSE;
		
		$return = DB::delete(self::tableName())
			->where('id', '=', $this->id )
			->execute();

        if ( ! $this->afterDelete()) 
		{
            $this->save();
            return FALSE;
        }

        return $return;
    }
    
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
        // Run it !!...
        return DB::insert(self::tableName($class_name))
			->columns( array_keys( $data ) )
			->values( array_values( $data ) )
			->execute();
    }
    
    public static function update($class_name, $data, $where, $values = array())
    {
		$sql = (string) DB::update(self::tableName($class_name))
			->set($data);

        return DB::query( Database::UPDATE, $sql . ' WHERE '.$where )
			->parameters($values)
			->execute();
    }
    
    public static function deleteWhere($class_name, $where, $values = array())
    {
        $sql = 'DELETE FROM '.self::tableName($class_name).' WHERE '.$where;

		return DB::query(Database::DELETE, $sql)
			->parameters($values)
			->execute();
    }
    
	public static function findByIdFrom($class_name, $id)
	{
		return self::findOneFrom($class_name, 'id = :id', array(':id' => $id));
	}

	public static function findOneFrom($class_name, $where, $values = array())
	{
		$sql = 'SELECT * FROM '.self::tableName($class_name).' WHERE '.$where;

		return DB::query( Database::SELECT, $sql)
			->parameters( $values )
			->as_object($class_name)
			->execute()
			->current();
	}

	public static function findAllFrom($class_name, $where = FALSE, $values = array())
	{
		$sql = 'SELECT * FROM '.self::tableName($class_name).($where ? ' WHERE '.$where:'');

		return DB::query(Database::SELECT, $sql)
			->parameters( $values )
			->as_object($class_name)
			->execute()
			->as_array();
	}

	public static function countFrom($class_name, $where = FALSE, $values = array())
	{
		$sql = 'SELECT COUNT(*) AS nb_rows FROM '.self::tableName($class_name).($where ? ' WHERE '.$where:'');

		return (int) DB::query(Database::SELECT, $sql)
			->execute()
			->get('nb_rows', 0);
	}

	public function beforeSave() { return TRUE; }
    public function beforeInsert() { return TRUE; }
    public function beforeUpdate() { return TRUE; }
    public function beforeDelete() { return TRUE; }
    public function afterSave() { return TRUE; }
    public function afterInsert() { return TRUE; }
    public function afterUpdate() { return TRUE; }
    public function afterDelete() { return TRUE; }
}