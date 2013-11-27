<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Users
 * @category	Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_Users extends Controller_System_Backend {

	public $allowed_actions = array(
		'profile'
	);

	public function before()
	{
		if(in_array($this->request->action(), array('edit')) AND AuthUser::getId() == $this->request->param('id'))
		{
			$this->allowed_actions[] = $this->request->action();
		}

		parent::before();
		$this->breadcrumbs
			->add(__('Users'), Route::url( 'backend', array('controller' => 'users')));
	}
	
	public function action_index()
	{
		$this->template->title = __('Users');

		$users = ORM::factory('user');
		
		$pager = Pagination::factory(array(
			'total_items' => $users->reset(FALSE)->count_all(),
			'items_per_page' => 20
		));

		$this->template->content = View::factory( 'users/index', array(
			'users' => $users
				->group_by( 'user.id')
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

		$this->template->content = View::factory( 'users/edit', array(
			'action' => 'add',
			'user' => $user,
			'permissions' => array()
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
				$user->update_related_ids('roles', explode(',', $permissions));

				$data['user_id'] = $user->id;
				$user->profile
					->values($data)
					->create();
				
				Kohana::$log->add(Log::INFO, 'User :new_user has been added by :user', array(
					':new_user' => HTML::anchor(Route::url('backend', array(
						'controller' => 'users',
						'action' => 'profile',
						'id' => $user->id
					)), $user->username),
				))->write();

				Messages::success(__( '!' ) );
				Observer::notify( 'user_after_add', $user );
			}
		}
		catch (ORM_Validation_Exception $e)
		{
			Messages::errors( $e->errors('validation') );
			$this->go_back();
		}
		
		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go();
		}
		else
		{
			$this->go(array(
				'action' => 'edit',
				'id' => $user->id
			));
		}
	}
	
	
	
	public function action_profile()
	{
		$id = $this->request->param('id');
		
		if(empty($id) AND AuthUser::isLoggedIn())
		{
			$id = AuthUser::getId();
		}
		
		$user = ORM::factory('user', $id);
		
		if( ! $user->loaded() )
		{
			Messages::errors( __('User not found!') );
			$this->go();
		}
		
		$this->template->title = __(':user profile', array(':user' => $user->username));
		$this->breadcrumbs
			->add($this->template->title);
		
		$this->template->content = View::factory( 'users/profile', array(
			'user' => $user,
			'permissions' => $user->permissions_list()
		) );
	}


	public function action_edit( )
	{
		$id = $this->request->param('id');
		
		$user = ORM::factory('user', $id);
		
		if( ! $user->loaded() )
		{
			Messages::errors( __('User not found!') );
			$this->go( 'user' );
		}
		
		$this->_save_referer('account/login');

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $user );
		}

		$this->template->title = __('Edit user');
		$this->breadcrumbs
			->add(__(':user profile', array(':user' => $user->username)), Route::url('backend', array(
				'controller' => 'users',
				'action' => 'profile',
				'id' => $user->id
			)))
			->add($this->template->title);

		$this->template->content = View::factory( 'users/edit', array(
			'action' => 'edit',
			'user' => $user
		) );
	}

	private function _edit( $user )
	{
		$data = $this->request->post('user');
		$this->auto_render = FALSE;

		if( ACL::check('users.change_password') OR $user->id == AuthUser::getId() )
		{
			if ( strlen( $data['password'] ) == 0 )
			{
				unset( $data['password'], $data['password_confirm'] );
			}
		}
		else
		{
			unset( $data['password'] );
		}
		

		if( empty($data['notice'] ))
		{
			$data['notice'] = 0;
		}

		try
		{
			if ( $user->update_user($data, array(
				'email', 'username', 'password'
			)) )
			{
				$data['user_id'] = $user->id;
				$user->profile
					->values($data)
					->save();

				if ( Acl::check('users.change_roles') AND $user->id > 1 )
				{
					// now we need to add permissions
					$permissions = $this->request->post('user_permission');
					$user->update_related_ids('roles', explode(',', $permissions));
				}
				
				Kohana::$log->add(Log::INFO, 'User :new_user has been updated by :user', array(
					':new_user' => HTML::anchor(Route::url('backend', array(
						'controller' => 'users',
						'action' => 'profile',
						'id' => $user->id
					)), $user->username),
				))->write();

				Messages::success( __( 'User has been saved!' ) );
				Observer::notify( 'user_after_edit', $user );
			}
		}
		catch (ORM_Validation_Exception $e)
		{
			Messages::errors( $e->errors('validation') );
			$this->go_back();
		}

		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go();
		}
		else
		{
			$this->go(array(
				'action' => 'edit',
				'id' => $user->id
			));
		}
	}

	public function action_delete( )
	{
		$this->auto_render = FALSE;
		$id = $this->request->param('id');

		// security (dont delete the first admin)
		if ( $id <= 1 )
		{
			Kohana::$log->add(Log::INFO, ':user trying to delete administrator', array(
				':user_id' => $id,
			))->write();
			
			throw new Kohana_Exception( 'Action disabled!' );
		}

		// find the user to delete
		$user = ORM::factory('user', $id);

		if( ! $user->loaded() )
		{
			Messages::errors( __('User not found!') );
			$this->go();
		}

		if ( $user->delete() )
		{
			Kohana::$log->add(Log::INFO, 'User with id :user_id has been deleted by :user', array(
				':user_id' => $id,
			))->write();
			
			Messages::success( __( 'User has been deleted!' ) );
			Observer::notify( 'user_after_delete', $id );
		}
		else
		{
			Messages::errors( __( 'Something went wrong!' ) );
		}

		$this->go();
	}
}