<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Layout extends Controller_System_Backend {

	public $auth_required = array( 'administrator', 'developer' );
	
	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Layouts'), $this->request->controller());
	}

	function action_index()
	{
		$this->template->title = __('Layouts');

		$this->template->content = View::factory( 'layout/index', array(
			'layouts' => Model_File_Layout::find_all()
		) );
	}

	function action_add()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add();
		}
		
		$this->template->title = __('Add layout');
		$this->breadcrumbs
			->add($this->template->title);

		// check if user have already enter something
		$layout = Flash::get( 'post_data' );

		if ( empty( $layout ) )
		{
			$layout = new Model_File_Layout;
		}

		$this->template->content = View::factory( 'layout/edit', array(
			'action' => 'add',
			'layout' => $layout
		) );
	}

	function _add()
	{
		$data = $this->request->post();
		Flash::set( 'post_data', (object) $data );

		$layout = new Model_File_Layout( $data['name'] );
		$layout->content = $data['content'];

		try
		{
			$status = $layout->save();
		}
		catch(Validation_Exception $e)
		{
			$this->go_back();
		}

		if ( ! $status )
		{
			$this->go( URL::site( 'layout/add/' ) );
		}
		else
		{
			Messages::success( __( 'Layout <b>:name</b> has been added!', array( ':name' => $layout->name ) ) );
			Observer::notify( 'layout_after_add', array( $layout ) );
		}
		
		Session::instance()->delete('post_data');

		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( URL::site( 'layout' ) );
		}
		else
		{
			$this->go( URL::site( 'layout/edit/' . $layout->name ) );
		}
	}

	function action_edit( )
	{
		$layout_name = $this->request->param('id');
		$layout = new Model_File_Layout( $layout_name );

		if ( !$layout->is_exists() )
		{
			Messages::errors(__( 'Layout <b>:name</b> not found!', array( ':name' => $layout->name ) ) );
			$this->go( URL::site( 'layout' ) );
		}

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $layout );
		}

		$this->template->content = View::factory( 'layout/edit', array(
			'action' => 'edit',
			'layout' => $layout
		) );
	}

	function _edit( $layout )
	{
		$layout->name = $this->request->post('name');
		$layout->content = $this->request->post('content');
		
		try
		{
			$status = $layout->save();
		}
		catch(Validation_Exception $e)
		{
			$this->go_back();
		}

		if ( !$status )
		{
			$this->go_back();
		}
		else
		{
			Messages::success(__( 'Layout <b>:name</b> has been saved!', array( ':name' => $layout->name ) ) );
			Observer::notify( 'layout_after_edit', array( $layout ) );
		}

		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( URL::site( 'layout' ) );
		}
		else
		{
			$this->go_back();
		}
	}

	function action_delete( )
	{
		$this->auto_render = FALSE;
		$layout_name = $this->request->param('id');

		$layout = new Model_File_Layout( $layout_name );

		// find the user to delete
		if ( !$layout->is_used() )
		{
			if ( $layout->delete() )
			{
				Messages::success( __( 'Layout <b>:name</b> has been deleted!', array( ':name' => $layout_name ) ) );
				Observer::notify( 'layout_after_delete', array( $layout_name ) );
			}
			else
			{
				Messages::errors( __( 'Layout <b>:name</b> has not been deleted!', array( ':name' => $layout_name ) ) );
			}
		}
		else
		{
			Messages::errors( __( 'Layout <b>:name</b> is used! It <i>can not</i> be deleted!', array( ':name' => $layout_name ) ) );
		}

		$this->go_back();
	}

}

// end LayoutController class