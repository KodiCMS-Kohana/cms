<?php defined('SYSPATH') or die('No direct access allowed.');

class UserPermission extends Record
{
    const TABLE_NAME = 'user_permission';
    
    public $user_id = false;
    public $permission_id = false;
    
    public static function setPermissionsFor($user_id, $perms)
    {        
        // remove all perms of this user
		DB::delete(self::TABLE_NAME)
			->where('user_id', '=', (int) $user_id)
			->execute();
        
        // add the new perms
        foreach ($perms as $perm_name => $perm_id)
        {
			DB::insert(self::TABLE_NAME)
				->columns(array('user_id,', 'permission_id'))
				->values(array((int) $user_id, (int) $perm_id))
				->execute();
        }
    }

} // end UserPermission class