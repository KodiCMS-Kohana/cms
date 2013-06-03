<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */
class KodiCMS_User extends Record
{
    const TABLE_NAME = 'users';
	
	public function defaults()
	{
		return array(
			'id' => NULL,
			'language' => I18n::lang(),
			'roles' => array(),
			'username' => NULL
		);
	}

	public function getPermissions()
    {
        if ( !isset($this->id))
        {
            return array();
        }
 
        return DB::select('perm.id', 'name')
			->from(array(self::tableName('Model_Permission'), 'perm'))
			->join(array(self::tableName('Model_User_Permission'), 'user_perm'), 'left')
				->on('user_perm.role_id', '=', 'perm.id')
			->where('user_id', '=', $this->id)
			->as_object()
			->cached()
			->execute()
			->as_array('id', 'name');
    }
    
    public static function findBy($column, $value)
    {
        return Record::findOneFrom('User', array(
			'where' => array(array($column, '=', $value))));
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
    
    public static function find($clause = array())
    {
		$sql = DB::select('user.*')
			->select(array('creator.name', 'created_by_name'))
			->select(array('updator.name', 'updated_by_name'))
			->from(self::tableName())
			->join(array(User::tableName(), 'creator'), 'left')
				->on('creator.id', '=', 'user.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'user.updated_by_id');
		
        // Prepare SQL
        $sql = self::_conditions($sql, $clause);
        
        $query = $sql
			->as_object(__CLASS__)
			->execute();
        
        // Run!
        if (Arr::get($clause, 'limit') == 1)
        {
            return $query->current();
        }
        else
        {
            return $query->as_array('id');
        }
    }
    
    public static function findAll($clause = array())
    {
        return self::find($clause);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => array(array('user.id', '=', (int) $id)),
            'limit' => 1
        ));
    }

} // end User class