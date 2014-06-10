<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Task
 * @author		ButscHSter
 */
class KodiCMS_Task_Users_Add extends Minion_Task
{
	protected $_options = array(
		'username' => NULL,
		'password' => NULL,
		'email' => NULL,
		'roles' => NULL,
		'name' => NULL
	);

	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('username', 'not_empty')
			->rule('password', 'not_empty')
			->rule('email', 'not_empty')
			->rule('email', 'email');
	}

	protected function _execute(array $params)
	{
		$params['password_confirm'] = $params['password'];
		
		$user = ORM::factory('user')
			->values($params, array(
				'username', 
				'password', 
				'email')
			);
		
		if( empty($params['name']))
		{
			$params['name'] = $params['username'];
		}
		
		if(!empty($params['roles']))
		{
			$roles = explode(',', $params['roles']);
		}
		
		if( empty($roles) )
		{
			$roles = array('login');
		}

		if ( $user->create() )
		{
			$roles = DB::select('id')
				->from('roles')
				->where('name', 'in', $roles)
				->execute()
				->as_array(NULL, 'id');
			
			$params['user_id'] = $user->id;
			$user->profile
				->values($params, array('user_id', 'name'))
				->create();

			$user->update_related_ids('roles', $roles);
			
			Minion_CLI::write('==============================================');
			Minion_CLI::write(__('User successfully created'));
			Minion_CLI::write('==============================================');
			Minion_CLI::write($user->username);
		}
	}
}