<?php defined('SYSPATH') or die('No direct access allowed.');

class User extends Record
{
    const TABLE_NAME = 'users';
    
    public $name = '';
    public $email = '';
    public $username = '';
    
    public $created_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    
    public function getPermissions()
    {
        if ( !isset($this->id))
        {
            return array();
        }
 
        return DB::select('perm.id', 'name')
			->from(array(self::tableName('Permission'), 'perm'))
			->join(array(self::tableName('UserPermission'), 'user_perm'), 'left')
				->on('user_perm.role_id', '=', 'perm.id')
			->where('user_id', '=', $this->id)
			->as_object()
			->cached()
			->execute()
			->as_array('id', 'name');
    }
    
    public static function findBy($column, $value)
    {
        return Record::findOneFrom('User', $column.' = :id', array(':id' => $value));
    }
    
    public function beforeInsert()
    {
        $this->created_by_id = AuthUser::getId();
        $this->created_on = date('Y-m-d H:i:s');
        return true;
    }
    
    public function beforeUpdated()
    {
        $this->updated_by_id = AuthUser::getId();
        $this->updated_on = date('Y-m-d H:i:s');
        return true;
    }
    
    public static function find($args = null)
    {
        
        // Collect attributes...
        $where    = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) : '';
        $offset   = isset($args['offset']) ? (int) $args['offset'] : 0;
        $limit    = isset($args['limit']) ? (int) $args['limit'] : 0;
        
        // Prepare query parts
        $where_string = empty($where) ? '' : "WHERE $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        $tablename = self::TABLE_NAME;
        
		$sql = (string) DB::select('user.*')
			->select(array('creator.name', 'created_by_name'))
			->select(array('updator.name', 'updated_by_name'))
			->from(self::tableName())
			->join(array(User::tableName(), 'creator'), 'left')
				->on('creator.id', '=', 'user.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'user.updated_by_id');
		
        // Prepare SQL
        $sql .= " $where_string $order_by_string $limit_string";
        
        $query = DB::query(Database::SELECT, $sql)
			->as_object(__CLASS__)
			->execute();
        
        // Run!
        if ($limit == 1)
        {
            return $query->current();
        }
        else
        {
            return $query->as_array('id');
        }
    
    }
    
    public static function findAll($args = null)
    {
        return self::find($args);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => 'user.id='.(int)$id,
            'limit' => 1
        ));
    }

} // end User class