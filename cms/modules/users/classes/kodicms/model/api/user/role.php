<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Users
 * @category	Model/API
 * @author		ButscHSter
 */
class KodiCMS_Model_API_User_Role extends Model_API {
	
	protected $_table_name = 'roles';

	public function get_all($uids, $fields = array(), $user_id = NULL)
	{
		$uids = $this->prepare_param($uids, array('Valid', 'numeric'));
		$fields = $this->prepare_param($fields);
		
		$roles = DB::select('id')
			->select_array( $this->filtered_fields( $fields ) )
			->from($this->table_name());
		
		if(!empty($uids))
		{
			$roles->where('id', 'in', $uids);
		}
		
		if($user_id !== NULL)
		{
			$roles
				->join('roles_users', 'left')
				->on('roles_users.role_id', '=', $this->table_name() . '.id')
				->where('roles_users.user_id', '=', (int) $user_id);
		}
		
		return $roles
			->execute()
			->as_array();
	}
}