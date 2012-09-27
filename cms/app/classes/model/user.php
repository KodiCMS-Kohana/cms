<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User {
	
	protected $_reload_on_wakeup = FALSE;
	
	protected $_roles = array();

	protected $_has_many = array(
		'user_tokens' => array('model' => 'user_token'),
		'roles'       => array('model' => 'role', 'through' => 'roles_users'),
	);

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
	
	public function generate_password($length = 6, $level = 2)
	{
		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));

		$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

		$password  = "";
		$counter   = 0;

		while ($counter < $length) 
		{
			$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

			// All character must be different
			if (!strstr($password, $actChar)) 
			{
				$password .= $actChar;
				$counter++;
			}
		}

		unset($counter);
		return $password;
	}
	
	public function change_email($new_email)
	{
		if(!$this->loaded())
		{
			throw new Kohana_Exception( ' User mast be loaded' );
		}
	
		$this->email = $new_email;
		return $this->update();
	}
}