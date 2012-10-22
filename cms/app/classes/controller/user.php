<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_User extends Controller_System_Backend {

	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Users'), $this->request->controller());
	}
	
	public function action_index()
	{
		$users = DB::select( 'user.*', array( 'GROUP_CONCAT("permission.name")', 'roles' ) )
			->from( array(User::tableName(), 'user') )
			->join( array( UserPermission::tableName(), 'user_permission'), 'left' )
				->on( 'user.id', '=', 'user_permission.user_id' )
			->join( array( Permission::tableName(), 'permission'), 'left' )
				->on( 'user_permission.role_id', '=', 'permission.id' )
			->group_by( 'user.id')
			->as_object('Model_User')
			->execute();

		$this->template->content = View::factory( 'user/index', array(
			'users' => $users
		) );
	}

	public function action_add()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add();
		}
		
		$this->breadcrumbs
			->add(__('Add user'));

		// check if user have already enter something
		$data = Flash::get( 'post_data', array() );

		$user = new User( $data );

		$this->template->content = View::factory( 'user/edit', array(
			'action' => 'add',
			'user' => $user,
			'permissions' => Permission::get_all()
		) );
	}

	private function _add()
	{
		$data = $this->request->post('user');
		$permissions = $this->request->post('user_permission');

		Flash::set( 'post_data', $data );
		
		try
		{
			$this->_valid($data);
		}
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		
		$user = new User( $data );

		if ( $user->save() )
		{
			UserPermission::setPermissionsFor( $user->id, $permissions );
			
			Messages::success(__( 'User has been added!' ) );
			Observer::notify( 'user_after_add', array( $user ) );
		}
		else
		{
			Flash::set( 'error', __( 'User <b>:name</b> has not been added!', array( ':name' => $user->name ) ) );
		}

		$this->go( URL::site( 'user' ) );
	}

	public function action_edit( )
	{
		$id = $this->request->param('id');

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $id );
		}

		$user = DB::select( 'user.*', array( 'GROUP_CONCAT("user_permission.role_id")', 'roles' ) )
			->from( array(User::tableName(), 'user') )
			->join( array( UserPermission::tableName(), 'user_permission'), 'left' )
				->on( 'user.id', '=', 'user_permission.user_id' )
			->where( 'id', '=', (int) $id )
			->limit( 1 )
			->as_object('Model_User')
			->execute()
			->current();

		if ( $user === NULL )
		{
			throw new Kohana_Exception('User not found!');
		}
		
		$user->roles = explode(',', $user->roles);

		$this->breadcrumbs
			->add(__('Edit user'));

		$this->template->content = View::factory( 'user/edit', array(
			'action' => 'edit',
			'user' => $user,
			'permissions' => Permission::get_all()
		) );
	}

// edit

	private function _edit( $id )
	{
		$data = $this->request->post('user');
		$this->auto_render = false;
		
		try
		{
			$this->_valid($data);
		}
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}

		// check if user want to change the password
		if ( strlen( $data['password'] ) > 0 )
		{
			$data['password'] = Auth::instance()->hash_password( $data['password'] );
		}
		else
		{
			unset( $data['password'] );
		}

		$user = Record::findByIdFrom( 'User', $id );
		$user->setFromData( $data, array('confirm') );

		if ( $user->save() )
		{
			if ( AuthUser::hasPermission( 'administrator' ) )
			{
				// now we need to add permissions
				$permissions = $this->request->post('user_permission');
				UserPermission::setPermissionsFor( $user->id, $permissions );
			}

			Messages::success( __( 'User <b>:name</b> has been saved!', array( ':name' => $user->name ) ) );
			Observer::notify( 'user_after_edit', array( $user->name ) );
		}
		else
		{
			Messages::errors( __( 'User <b>:name</b> has not been saved!', array( ':name' => $user->name ) ) );
		}

		if ( AuthUser::getId() == $id )
		{
			$this->go( URL::site( 'user/edit/' . $id ) );
		}
		else
		{
			$this->go( URL::site( 'user' ) );
		}
	}

	public function action_delete( )
	{
		$this->auto_render = FALSE;
		$id = $this->request->param('id');

		// security (dont delete the first admin)
		if ( $id <= 1 )
		{
			throw new Kohana_Exception( 'Action disabled!' );
		}

		// find the user to delete
		$user = Record::findByIdFrom( 'User', $id );

		if ( !$user )
		{
			throw new Kohana_Exception( 'User not found!' );
		}

		if ( $user->delete() )
		{
			Messages::success( __( 'User <b>:name</b> has been deleted!', array( ':name' => $user->name ) ) );
			Observer::notify( 'user_after_delete', array( $user->name ) );
		}
		else
		{
			Messages::errors( __( 'User <b>:name</b> has not been deleted!', array( ':name' => $user->name ) ) );
		}

		$this->go( URL::site( 'user' ) );
	}
	
	protected function _valid(array $data)
	{
		$array = Validation::factory($data)
			->rules('username', array(
				array('not_empty'),
				array('min_length', array(':value', 3))
			))
			->rules('email', array(
				array('not_empty'),
				array('email'),
				array('min_length', array(':value', 5))
			));
		
		if(!empty($data['password']) OR $this->request->action() == 'add')
		{
			$array
				->rule('password', 'not_empty')
				->rule('password', 'min_length', array(':value', 8))
				->rule('confirm', 'matches', array(':validation', ':field', 'password'));
		}
		
		if(!$array->check())
		{
			throw new Validation_Exception($array);
		}
	}

}

// end UserController class