<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Api
 */
class Model_API_User extends Model_API {
	
	protected $_table_name = 'users';
	
	protected $_secured_columns = array(
		'email', 'logins', 'last_login', 'password
'	);

	public function get($uids, $fields)
	{
		$uids = $this->prepare_param($uids, array('Valid', 'numeric'));
		$fields = $this->prepare_param($fields);
		
		$users = DB::select($this->table_name() . '.id', $this->table_name() . '.username')
			->select_array( $this->filtered_fields( $fields, array('password') ) )
			->from($this->_table_name);
		
		if(!empty($uids))
		{
			$users->where('id', 'in', $uids);
		}
		
		if(in_array('roles', $fields))
		{
			$users
				->select(array( DB::expr('GROUP_CONCAT('.$this->_db->quote_column('roles.name').')'), 'roles' ))
				->join('roles_users', 'left')
					->on('roles_users.user_id', '=', $this->table_name() . '.id')
				->join('roles', 'left')
					->on('roles_users.role_id', '=', 'roles.id')
				->group_by( $this->table_name() . '.id' );
		}

		return $users
			->execute()
			->as_array();
	}
}