<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_User extends Controller_System_Backend {

	public function action_index()
	{
		$users = DB::select( 'user.*', array( 'GROUP_CONCAT("permission.name")', 'roles' ) )
			->from( array(User::tableName(), 'user') )
			->join( array( UserPermission::tableName(), 'user_permission'), 'left' )
				->on( 'user.id', '=', 'user_permission.user_id' )
			->join( array( Permission::tableName(), 'permission'), 'left' )
				->on( 'user_permission.role_id', '=', 'permission.id' )
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
		
		$this->template->breadcrumbs = array(
			HTML::anchor( 'user', __('Users')),
			__('Add user')
		);

		// check if user have already enter something
		$user = Flash::get( 'post_data' );

		if ( empty( $user ) )
		{
			$user = new User;
			$user->language = I18n::lang();
			$user->id = NULL;
			$user->roles = '';
		}

		$this->template->content = View::factory( 'user/edit', array(
			'action' => 'add',
			'user' => $user,
			'permissions' => Record::findAllFrom( 'Permission' )
		) );
	}

	private function _add()
	{
		$data = $_POST['user'];

		Flash::set( 'post_data', (object) $data );

		// check if pass and confirm are egal and >= 5 chars
		if ( strlen( $data['password'] ) >= 5 && $data['password'] == $data['confirm'] )
		{
			$data['password'] = sha1( $data['password'] );
			unset( $data['confirm'] );
		}
		else
		{
			Messages::errors( __( 'Password and Confirm are not the same or too small!' ) );
			$this->go( URL::site( 'user/add' ) );
		}

		// check if username >= 3 chars
		if ( strlen( $data['username'] ) < 3 )
		{
			Messages::errors( __( 'Username must contain a minimum of 3 characters!' ) );
			$this->go( URL::site( 'user/add' ) );
		}

		$user = new User( $data );

		if ( $user->save() )
		{
			// now we need to add permissions if needed
			if ( !empty( $_POST['user_permission'] ) )
			{
				UserPermission::setPermissionsFor( $user->id, $_POST['user_permission'] );
			}

			Messages::success(__( 'User has been added!' ) );
			Observer::notify( 'user_after_add', array( $user->name ) );
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


		if ( $user !== NULL )
		{
			$roles = DB::select()
				->from( Permission::tableName() )
				->as_object()
				->execute();
			
			$this->template->breadcrumbs = array(
				HTML::anchor( 'user', __('Users')),
				__('Edit user :user', array(':user' => $user->username))
			);

			$this->template->content = View::factory( 'user/edit', array(
				'action' => 'edit',
				'user' => $user,
				'permissions' => $roles
			) );

			return;
		}
		else
		{
			Messages::errors( __( 'User not found!' ) );
		}

		$this->go( URL::site( 'user' ) );
	}

// edit

	private function _edit( $id )
	{
		$data = $_POST['user'];
		$this->auto_render = false;

		// check if user want to change the password
		if ( strlen( $data['password'] ) > 0 )
		{
			// check if pass and confirm are egal and >= 5 chars
			if ( strlen( $data['password'] ) >= 5 && $data['password'] == $data['confirm'] )
			{
				$data['password'] = sha1( $data['password'] );
				unset( $data['confirm'] );
			}
			else
			{
				Flash::set( 'error', __( 'Password and Confirm are not the same or too small!' ) );
				$this->go( URL::site( 'user/edit/' . $id ) );
			}
		}
		else
		{
			unset( $data['password'], $data['confirm'] );
		}

		$user = Record::findByIdFrom( 'User', $id );
		$user->setFromData( $data );

		if ( $user->save() )
		{
			

			if ( AuthUser::hasPermission( 'administrator' ) )
			{
				// now we need to add permissions
				$data = Arr::get($_POST, 'user_permission', array());
				UserPermission::setPermissionsFor( $user->id, $data );
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
		$this->auto_render = false;
		$id = $this->request->param('id');

		// security (dont delete the first admin)
		if ( $id > 1 )
		{
			// find the user to delete
			if ( $user = Record::findByIdFrom( 'User', $id ) )
			{
				if ( $user->delete() )
				{
					Messages::success( __( 'User <b>:name</b> has been deleted!', array( ':name' => $user->name ) ) );
					Observer::notify( 'user_after_delete', array( $user->name ) );
				}
				else
				{
					Messages::errors( __( 'User <b>:name</b> has not been deleted!', array( ':name' => $user->name ) ) );
				}
			}
			else
			{
				Messages::errors( __( 'User not found!' ) );
			}
		}
		else
		{
			Messages::errors( __( 'Action disabled!' ) );
		}

		$this->go( URL::site( 'user' ) );
	}

}

// end UserController class