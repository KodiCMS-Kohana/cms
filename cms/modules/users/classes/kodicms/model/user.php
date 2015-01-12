<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_User extends Model_Auth_User {
	
	const DEFAULT_LOCALE = 'st-df';
	
	protected $_reload_on_wakeup = FALSE;
	
	/**
	 * Список ролей пользователя
	 * @var array 
	 */
	protected $_roles = NULL;

	protected $_has_many = array(
		'user_tokens' => array('model' => 'user_token'),
		'roles'       => array('model' => 'role', 'through' => 'roles_users'),
		'socials'	  => array('model' => 'user_social')
	);
	
	protected $_has_one = array(
		'profile' => array('model' => 'user_profile'),
    );
	
	public function labels()
	{
		return array(
			'username'         => __('Username'),
			'email'            => __('E-mail'),
			'password'         => __('Password'),
			'password_confirm' => __('Confirm Password')
		);
	}
	
	public function form_columns()
	{
		return array(
			'id' => array(
				'type' => 'input',
				'editable' => FALSE,
				'length' => 10
			),
			'password' => array(
				'type' => 'password'
			),
			'password_confirm' => array(
				'type' => 'password',
				'free' => TRUE
			)
		);
	}
	
	/**
	 * Добавление в запрос получения спсика ролей
	 * 
	 * @return ORM
	 */
	public function with_roles()
	{
		$role = ORM::factory('role');
		return $this
			->select( array( DB::expr('GROUP_CONCAT('.Database::instance()->quote_column('permission.name').')'), 'roles' ) )
			->join( array( 'roles_users', 'user_permission'), 'left' )
				->on( 'user.id', '=', 'user_permission.user_id' )
			->join( array( $role->table_name(), 'permission'), 'left' )
				->on( 'user_permission.role_id', '=', 'permission.id' );
	}

	/**
	 * Проверка на существование роли у пользователя
	 * 
	 * @param array|string $role
	 * @param boolean $all_required
	 * @return boolean
	 */
	public function has_role($role, $all_required = TRUE) 
	{
		$status = TRUE;
		
		if(is_array($role))
		{
			$status = (bool) $all_required;
			
			foreach ($role as $_role)
			{
				// If the user doesn't have the role
				if ( !in_array($_role, $this->roles()))
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
			$status = in_array($role, $this->roles());
		}
		
		return $status;
	}
	
	
	/**
	 * Получение аватара пользлователя из сервиса Gravatar
	 * 
	 * @param integer $size
	 * @param string $default
	 * @param array $attributes
	 * @return string HTML::image
	 */
	public function gravatar($size = 40, $default = NULL, array $attributes = NULL)
	{
		return Gravatar::load($this->email, $size, $default, $attributes);
	}

	/**
	 * Список ролей пользователя
	 * 
	 * @return array
	 */
	public function roles()
	{
		if($this->_roles === NULL)
		{
			$this->_roles = $this->roles
				->find_all()
				->as_array('id', 'name');
		}
		
		return $this->_roles;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function permissions()
	{
		$permissions = array();
		$roles = $this->roles();
		
		if( ! empty($roles) )
		{
			$permissions = DB::select('action')
				->from('roles_permissions')
				->where('role_id', 'in', array_keys($roles))
				->execute()
				->as_array(NULL, 'action');
		}
		
		return array_unique($permissions);
	}
	
	/**
	 * Список прав пользователя
	 * 
	 * @return array
	 */
	public function permissions_list()
	{
		$permissions = array();

		foreach(Acl::get_permissions() as $section_title => $actions)
		{
			foreach($actions as $action => $title)
			{
				if( Acl::check($action, $this) )
				{
					$permissions[$section_title][$action] = $title;
				}
			}
		}
		
		return $permissions;
	}

	/**
	 * Переопределение метода для подгрузки списка ролей пользователя.
	 */
	public function complete_login()
	{
		if ($this->_loaded)
		{
			$this->roles();
		}

		parent::complete_login();
	}
	
	/**
	 * Смена email адреса 
	 * 
	 * @param string $email
	 * @return ORM
	 * @throws Kohana_Exception
	 */
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
	
	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', Config::get('auth', 'password_length')))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}
	
	/**
	 * Получение языкового кода усатновленного в профиле
	 * 
	 * @return string
	 */
	public static function locale()
	{
		$user = Auth::get_record();
		
		if($user instanceof Model_User)
		{
			$locale = $user->profile->get('locale');
			
			if($locale != Model_User::DEFAULT_LOCALE)
			{
				return $user->profile->get('locale');
			}
		}
		
		return Config::get('site', 'default_locale');
	}

	/**
	 *  
	 * @return string
	 */
	public function serialize()
	{
		$parameters = array(
			'_primary_key_value', '_object', '_changed', '_loaded', '_saved', '_sorting'
		);
		
		// Store only information about the object
		foreach ($parameters as $var)
		{
			$data[$var] = $this->{$var};
		}

		return Kohana::serialize($data);
	}

	/**************************************************************************
	 * Events
	 **************************************************************************/
	public function before_create()
	{
		Observer::notify( 'user_before_add', $this );
		
		return parent::before_create();
	}
	
	public function after_create()
	{	
		Kohana::$log->add(Log::INFO, 'User :new_user has been added by :user', array(
			':new_user' => HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'users',
				'action' => 'profile',
				'id' => $this->id
			)), $this->username),
		))->write();

		Observer::notify( 'user_after_add', $this );
		
		return parent::after_create();
	}
	
	public function before_update()
	{
		Observer::notify( 'user_before_update', $this );
		
		return parent::before_update();
	}
	
	public function after_update()
	{
		Kohana::$log->add(Log::INFO, 'User :new_user has been updated by :user', array(
			':new_user' => HTML::anchor(Route::get('backend')->uri(array(
				'controller' => 'users',
				'action' => 'profile',
				'id' => $this->id
			)), $this->username),
		))->write();

		Observer::notify('user_after_update', $this);
		return parent::after_update();
	}
	
	public function before_delete()
	{
		// security (dont delete the first admin)
		if ($this->id == 1)
		{
			Kohana::$log->add(Log::INFO, ':user trying to delete administrator', array(
				':user_id' => $this->id,
			))->write();

			return FALSE;
		}

		Observer::notify('user_before_delete', $this);

		return parent::before_delete();
	}
	
	public function after_delete($id)
	{
		Kohana::$log->add(Log::INFO, 'User with id :user_id has been deleted by :user', array(
			':user_id' => $id,
		))->write();

		Observer::notify('user_after_delete', $id);

		return parent::after_delete($id);
	}
}