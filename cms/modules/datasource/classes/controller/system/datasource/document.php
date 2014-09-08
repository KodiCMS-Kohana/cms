<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_System_Datasource_Document extends Controller_System_Datasource
{
	public function before()
	{
		$ds_id = (int) Arr::get($this->request->query(), 'ds_id', $this->request->post('ds_id'));
		
		$this->section($ds_id);
		
		$this->_check_acl($this->section()->type(), $ds_id);

		parent::before();
	}

	public function action_create()
	{
		return $this->action_view();
	}

	public function action_view()
	{
		Assets::package('backbone');
		$id = (int) $this->request->query('id');
		
		$doc = $this->_get_document($id);
		
		WYSIWYG::load_filters();
		
		$this->_load_session_data($doc);
		
		$doc->onControllerLoad();
		
		$this->breadcrumbs
			->add($this->section()->name, Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'data'
			)) . URL::query(array('ds_id' => $this->section()->id()), FALSE));
		
		if( ! $doc->loaded() )
		{
			$this->set_title(__('New document'));
		}
		else
		{
			$this->set_title($doc->header);
		}
		
		$this->_load_template($doc);
	}
	
	/**
	 * 
	 * @param Datasource_Section $ds
	 * @param Datasource_Document $doc
	 */
	public function action_post()
	{
		$id = (int) $this->request->post('id');
		$doc = $this->_get_document($id);
		
		Session::instance()->set('post_data', $this->request->post());

		try
		{
			$doc
				->read_values($this->request->post())
				->read_files($_FILES)
				->validate();
		} 
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}

		if( $doc->loaded() )
		{
			$this->section()->update_document($doc);
		}
		else
		{
			$doc = $this->section()->create_document($doc);
		}

		Messages::success(__('Document saved'));
		
		Session::instance()->delete('post_data');
		
		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go(Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'data'
			)) . URL::query(array('ds_id' => $this->section()->id()), FALSE));
		}
		else
		{
			$this->go(Route::get('datasources')->uri(array(
				'directory' => $this->section()->type(),
				'controller' => 'document',
				'action' => 'view'
			)) . URL::query(array('ds_id' => $this->section()->id(), 'id' => $doc->id), FALSE));
		}
	}
	
	/**
	 * 
	 * @param Datasource_Document $doc
	 */
	protected function _load_template($doc) 
	{
		$this->template->content = View::factory('datasource/'.$this->section()->type().'/document/edit')->set( array(
			'ds' => $this->section(),
			'doc' => $doc,
			'action' => $this->request->action()
		));
	}
	
	/**
	 * 
	 * @param Datasource_Document $doc
	 * return Datasource_Document
	 */
	protected function _load_session_data($doc)
	{
		$post_data = Session::instance()->get_once('post_data');
		
		if( ! empty($post_data))
		{
			unset($post_data['id']);
			$doc->read_values($post_data);
		}
		
		return $doc;
	}
	
	/**
	 * 
	 * @param string $type
	 * @param integer $ds_id
	 */
	protected function _check_acl($type, $ds_id)
	{
		if(
			Acl::check($type.$ds_id.'.document.edit')
		OR
			Acl::check($type.$ds_id.'.document.view')
		)
		{
			$this->allowed_actions[] = 'view';
		}
		
		if(
			Acl::check($type.$ds_id.'.document.edit')
		)
		{
			$this->allowed_actions[] = 'create';
		}
	}
	
	protected function _get_document($id)
	{
		if( empty($id) )
		{
			$doc = $this->section()->get_empty_document();
		}
		else
		{
			$doc = $this->section()->get_document($id);
			
			if(!$doc)
			{
				throw new HTTP_Exception_404('Document ID :id not found', 
						array(':id' => $id));
			}
		}
		
		return $doc;
	}
}