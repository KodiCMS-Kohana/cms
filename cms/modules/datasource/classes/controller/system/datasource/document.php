<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_System_Datasource_Document extends Controller_System_Datasource
{
	public function before()
	{
		$ds_id = (int) $this->request->query('ds_id');
		
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
		$action = $this->request->action();

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

		if($this->request->method() === Request::POST)
		{
			return $this->_save($this->section(), $doc);
		}
		
		WYSIWYG::load_filters();
		
		$this->_load_session_data($doc);
		
		$this->breadcrumbs
			->add($this->section()->name, Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'data'
			)) . URL::query(array('ds_id' => $this->section()->id()), FALSE));
		
		if( ! $doc->loaded() )
		{
			$this->template->title = __('New document');
		}
		else
		{
			$this->template->title = $doc->header;
		}
		
		$this->breadcrumbs->add($this->template->title);
		
		$this->_load_template($doc);
	}
	
	/**
	 * 
	 * @param Datasource_Section $ds
	 * @param Datasource_Document $doc
	 */
	private function _save($ds, $doc)
	{
		Session::instance()->set('post_data', $this->request->post());

		try
		{
			$doc
				->read_values($this->request->post())
				->read_files($_FILES)
				->validate();

			if( $doc->loaded() )
			{
				$ds->update_document($doc);
			}
			else
			{
				$doc = $ds->create_document($doc);
			}
			
			Messages::success(__('Document saved'));
		} 
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		
		Session::instance()->delete('post_data');
		
		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go(Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'data'
			)) . URL::query(array('ds_id' => $ds->id()), FALSE));
		}
		else
		{
			$this->go(Route::get('datasources')->uri(array(
				'directory' => $ds->type(),
				'controller' => 'document',
				'action' => 'view'
			)) . URL::query(array('ds_id' => $ds->id(), 'id' => $doc->id), FALSE));
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
}