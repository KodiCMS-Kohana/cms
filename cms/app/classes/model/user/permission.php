<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */

class Model_User_Permission extends Record
{
    const TABLE_NAME = 'roles_users';
    
    public $user_id = false;
    public $permission_id = false;
    
    public static function setPermissionsFor($user_id, $permissions)
    {
		if( ! is_array( $permissions ))
		{
			$permissions = array($permissions);
		}
		
		if(empty($permissions))
		{
			return NULL;
		}

        // remove all perms of this user
		DB::delete(self::tableName())
			->where('user_id', '=', (int) $user_id)
			->execute();
		
		$insert = DB::insert(self::tableName())
				->columns(array('user_id', 'role_id'));
        
        // add the new perms
        foreach ($permissions as $name => $id)
        {
			$insert
				->values(array((int) $user_id, (int) $id));
        }
		
		return $insert->execute();
    }

} // end Model_User_Permission class