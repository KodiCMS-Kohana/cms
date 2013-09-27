<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class KodiCMS_Controller_Roles extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Users'), Route::url( 'backend', array('controller' => 'users')))
			->add(__('Roles'), Route::url( 'backend', array('controller' => 'roles')));
	}

	public function action_index()
	{
		$this->template->title = __('Roles');
		
		$roles = ORM::factory('role');
		
		$pager = Pagination::factory(array(
			'total_items' => $roles->reset(FALSE)->count_all(),
			'items_per_page' => 20
		));
		
		$this->template->content = View::factory( 'roles/index', array(
			'roles' => $roles
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

		$role = ORM::factory('role')
			->values($data);
		
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add($role);
		}
		
		$this->template->title = __('Add role');
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'roles/edit', array(
			'action' => 'add',
			'role' => $role
		) );
	}
	
	private function _add($role)
	{
		$data = $this->request->post('role');
		$this->auto_render = FALSE;
		
		Flash::set( 'post_data', $data );

		$role->values($data);

		try 
		{
			if ( $role->create() )
			{
				if (Acl::check( 'roles.change_permissions') )
				{
					$role->set_permissions($data['permissions']);
				}

				Messages::success(__( 'Role has been added!' ) );
				Observer::notify( 'role_after_add', array( $role ) );
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
			$this->go( array(
				'action' => 'edit',
				'id' => $role->id
			));
		}
	}

	public function action_edit( )
	{
		$id = $this->request->param('id');
		
		$role = ORM::factory('role', $id);
		
		if( ! $role->loaded() )
		{
			Messages::errors( __('Role not found!') );
			$this->go();
		}

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $role );
		}

		$this->template->title = __('Edit role');
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'roles/edit', array(
			'action' => 'edit',
			'role' => $role
		) );
	}

	private function _edit( $role )
	{
		$data = $this->request->post('role');
		$this->auto_render = FALSE;

		$role->values($data);

		try
		{
			if ( $role->update() )
			{
				if (Acl::check( 'roles.change_permissions') )
				{
					$role->set_permissions($data['permissions']);
				}

				Messages::success( __( 'Role has been saved!' ) );
				Observer::notify( 'role_after_edit', $role );
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
			$this->go( array(
				'action' => 'edit',
				'id' => $role->id
			));
		}
	}

	public function action_delete( )
	{
		$this->auto_render = FALSE;
		$id = $this->request->param('id');

		// security (dont delete the first admin)
		if ( $id < 2 )
		{
			throw new Kohana_Exception( 'Action disabled!' );
		}

		$role = ORM::factory('role', $id);

		if( ! $role->loaded() )
		{
			Messages::errors( __('Role not found!') );
			$this->go();
		}

		if ( $role->delete() )
		{
			Messages::success( __( 'Role has been deleted!' ) );
			Observer::notify( 'role_after_delete', $role->name );
		}
		else
		{
			Messages::errors( __( 'Something went wrong!' ) );
		}

		$this->go();
	}
}