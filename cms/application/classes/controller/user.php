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
		$this->template->title = __('Users');

		$users = ORM::factory('user')
			->group_by( 'user.id');
		
		$pager = Pagination::factory(array(
			'total_items' => $users->reset(FALSE)->count_all(),
			'items_per_page' => 20
		));

		$this->template->content = View::factory( 'user/index', array(
			'users' => $users
				->with_roles()
				->limit($pager->items_per_page)
				->offset($pager->offset)
				->find_all(),
			'pager' => $pager
		) );
	}

	public function action_add()
	{
		// check if user have already enter something
		$data = Flash::get( 'post_data', array() );

		$user = ORM::factory('user')
			->values($data);
		
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add($user);
		}
		
		$this->template->title = __('Add user');
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'user/edit', array(
			'action' => 'add',
			'user' => $user,
			'permissions' => Model_Permission::get_all()
		) );
	}

	private function _add($user)
	{
		$data = $this->request->post('user');
		$permissions = $this->request->post('user_permission');
		$this->auto_render = FALSE;
		
		if( empty($data['notice'] ))
		{
			$data['notice'] = 0;
		}
		
		Flash::set( 'post_data', $data );

		$user->values($data);

		try 
		{
			if ( $user->create() )
			{
				$user->update_related_ids('roles', $permissions);

				$data['user_id'] = $user->id;
				$user->profile
					->values($data)
					->create();

				Messages::success(__( 'User has been added!' ) );
				Observer::notify( 'user_after_add', array( $user ) );
			}
		}
		catch (ORM_Validation_Exception $e)
		{
			Flash::set( 'error', __( 'User <b>:name</b> has not been added!', array( ':name' => $user->name ) ) );
			$this->go_back();
		}
		
		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( 'user' );
		}
		else
		{
			$this->go( 'user/edit/' . $user->id );
		}
	}

	public function action_edit( )
	{
		$id = $this->request->param('id');
		
		$user = ORM::factory('user', $id);
		
		if( ! $user->loaded() )
		{
			throw new HTTP_Exception_404('User not found!');
		}

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $user );
		}

		$this->template->title = __('Edit user');
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'user/edit', array(
			'action' => 'edit',
			'user' => $user,
			'permissions' => Model_Permission::get_all()
		) );
	}

	private function _edit( $user )
	{
		$data = $this->request->post('user');
		$this->auto_render = FALSE;

		// check if user want to change the password
		if ( strlen( $data['password'] ) == 0 )
		{
			unset( $data['password'] );
		}
		
		if( empty($data['notice'] ))
		{
			$data['notice'] = 0;
		}

		$user->values($data);

		try
		{
			if ( $user->update() )
			{
				$data['user_id'] = $user->id;
				$user->profile
					->values($data)
					->save();

				if ( AuthUser::hasPermission( 'administrator' ) )
				{
					// now we need to add permissions
					$permissions = $this->request->post('user_permission');
					$user->update_related_ids('roles', $permissions);
				}

				Messages::success( __( 'User <b>:name</b> has been saved!', array( ':name' => $user->name ) ) );
				Observer::notify( 'user_after_edit', array( $user ) );
			}
		}
		catch (ORM_Validation_Exception $e)
		{
			Messages::errors( __( 'User <b>:name</b> has not been saved!', array( ':name' => $user->name ) ) );
			$this->go_back();
		}

		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( 'user' );
		}
		else
		{
			$this->go( 'user/edit/' . $user->id );
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
		$user = ORM::factory('user', $id);

		if ( ! $user->loaded() )
		{
			throw new HTTP_Exception_404( 'User not found!' );
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

		$this->go( 'user' );
	}
}

// end UserController class