<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User {
	
	protected $_reload_on_wakeup = FALSE;
	
	protected $_roles = array();

	protected $_has_many = array(
		'user_tokens' => array('model' => 'user_token'),
		'roles'       => array('model' => 'role', 'through' => 'roles_users'),
	);
	
	protected $_has_one = array(
		'profile' => array('model' => 'user_profile'),
    );
	
	public function with_roles()
	{
		return $this
			->select( array( DB::expr('GROUP_CONCAT('.Database::instance()->quote_column('permission.name').')'), 'roles' ) )
			->join( array( Model_User_Permission::tableName(), 'user_permission'), 'left' )
				->on( 'user.id', '=', 'user_permission.user_id' )
			->join( array( Model_Permission::tableName(), 'permission'), 'left' )
				->on( 'user_permission.role_id', '=', 'permission.id' );
	}
	
	public function filter_by_letter( $letter )
	{
		if( empty($letter) ) return $this;
	
		if( $letter == 'rus' )
		{
			$this
				->where('name', 'regexp', '^[а-я]');
		}
		else
		{
			$this->where('LEFT("name", 1)', 'like', '%' . $letter);
		}
		
		return $this;
	}

	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', Kohana::$config->load('auth')->get( 'password_length' )))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}

	public function has_role($role, $all_required = TRUE) 
	{
		$status = TRUE;
		
		if(is_array($role))
		{
			$status = (bool) $all_required;
			
			foreach ($role as $_role)
			{
				// If the user doesn't have the role
				if ( !in_array($_role, $this->_roles))
				{
					// Set the status false and get outta here
					$status = FALSE;

					if ($all_required)
					{
						break;
					}
				}
				elseif ( ! $all_required )
				{
				   $status = TRUE;
				   break;
				}
			}
		}
		else
		{
			$status = in_array($role, $this->_roles);
		}
		
		return $status;
	}
	
	public function gravatar($size = 40, $default = NULL, $attributes = array())
	{
		if($default === NULL)
		{
			$default = 'mm';
		}
	
		$hash = md5( strtolower( trim( $this->email ) ) );
		$query_params = URL::query(array(
			'd' => $default,
			's' => $size
		));
		
		return HTML::image('http://www.gravatar.com/avatar/' . $hash . $query_params, $attributes);
	}

	public function roles()
	{
		return $this->_roles;
	}
	
	public function complete_login()
	{
		$roles = $this->roles->find_all();
		
		foreach ($roles as $role)
		{
			$this->_roles[] = $role->name;
		}

		parent::complete_login();
	}

	public function serialize()
	{
		$parameters = array(
			'_primary_key_value', '_object', '_changed', '_loaded', '_saved', '_sorting', 
			'_roles'
		);
		
		// Store only information about the object
		foreach ($parameters as $var)
		{
			$data[$var] = $this->{$var};
		}

		return serialize($data);
	}
	
	public function change_email($email)
	{
		if(!$this->loaded())
		{
			throw new Kohana_Exception( ' User mast be loaded' );
		}
		
		return $this->update_user( array(
			'password' => $email, 'password_confirm' => $email
		));
	}
}