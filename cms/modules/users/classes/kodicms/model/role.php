<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_Role extends Model_Auth_Role {
	
	public function permissions()
	{
		return DB::select('action')
			->from('roles_permissions')
			->where('role_id', '=', $this->id)
			->execute()
			->as_array(NULL, 'action');
	}
	
	public function set_permissions( array $new_permissions = NULL )
	{
		DB::delete('roles_permissions')
			->where('role_id', '=', $this->id)
			->execute();
		
		if(!empty($new_permissions))
		{
			$insert = DB::insert('roles_permissions')
				->columns(array('role_id', 'action'));

			foreach($new_permissions as $action => $status)
			{
				$insert->values(array($this->id, $action));
			}
			
			$insert->execute();
		}
		
		return $this;
	}
}