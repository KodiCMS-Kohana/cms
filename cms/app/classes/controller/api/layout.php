<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Layout extends Controller_System_Api {
	
	public function before() 
	{
		define('REST_BACKEND', TRUE);
		parent::before();
	}

	public function rest_post()
	{
		$layout_name = $this->param('layout_name', NULL, TRUE);
		$layout = new Model_File_Layout( $layout_name );

		if ( ! $layout->is_exists() )
		{
			throw HTTP_API_Exception::factory(API::ERROR_PAGE_NOT_FOUND,
				'Layout :name not found!',
				array(':name' => $layout_name)
			);
		}

		$layout->name = $this->param('name', NULL);
		$layout->content = $this->param('content', NULL);

		try
		{
			$status = $layout->save();
		}
		catch(Validation_Exception $e)
		{
			throw new API_Validation_Exception($e->errors('validation'));
		}
		
		if ( ! $status )
		{
			$this->json['message'] = __( 'Layout :name has not been saved!', array( ':name' => $layout->name ) );
		}
		else
		{
			if($layout->name != $layout_name) 
			{
				$this->json_redirect('layout/edit/' . $layout->name);
			}

			$this->json['message'] = __( 'Layout :name has been saved!', array( ':name' => $layout->name ) );
			Observer::notify( 'layout_after_edit', array( $layout ) );
		}
		
		$this->response($layout);
	}
	
	public function rest_put()
	{
		
	}
	
	public function rest_delete()
	{
		$layout_name = $this->param('name', NULL, TRUE);
		
		$layout = new Model_File_Layout( $layout_name );
		
		if ( ! $layout->is_exists() )
		{
			throw HTTP_API_Exception::factory(API::ERROR_PAGE_NOT_FOUND,
				'Layout :name not found!',
				array(':name' => $layout_name)
			);
		}

		// find the user to delete
		if ( ! $layout->is_used() )
		{
			if ( $layout->delete() )
			{
				$this->response($layout);
			}
			else
			{
				throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
					'Layout <b>:name</b> has not been deleted!',
					array(':name' => $layout_name)
				);
			}
		}
		else
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS,
				'Layout :name is used! It can not be deleted!',
				array(':name' => $layout_name)
			);
		}
	}
}