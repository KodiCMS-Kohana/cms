<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Hybrid_Section extends Controller_System_Datasource
{
	public $ds = NULL;
	
	public function before()
	{
		if($this->request->action() != 'create')
		{
			$ds_id = (int) $this->request->param('id');
			$this->section($ds_id);

			if(Acl::check($this->section()->type().$ds_id.'.section.edit'))
			{
				$this->allowed_actions[] = 'edit';
			}
			
			if(Acl::check($this->section()->type().$ds_id.'.section.remove'))
			{
				$this->allowed_actions[] = 'remove';
			}
		}

		parent::before();
	}

	public function action_create()
	{
		if($this->request->method() === Request::POST)
		{
			return $this->_create();
		}

		$dss = Datasource_Data_Manager::get_all( 'hybrid' );
		
		$options = array(0 => __('None'));
		
		foreach ($dss as $ds_id => $ds)
		{
			$options[$ds_id] = $ds['name'];
		}

		$this->breadcrumbs
				->add(__('Add hybrid'));
		
		$this->template->content = View::factory('datasource/data/hybrid/create', array(
			'options' => $options
		));
	}
	
	private function _create()
	{
		$dsf = new DataSource_Hybrid_Factory();
		
		$array = Validation::factory($this->request->post())
			->rules('ds_key', array(
				array('not_empty'),
				array('DataSource_Hybrid_Factory::exists')
			))
			->rules('ds_name', array(
				array('not_empty')
			))
			->label( 'ds_name', __('Header') )
			->label( 'ds_key', __('Key') );
		
		if(!$array->check())
		{
			Messages::errors($array->errors('validation'));
			$this->go_back();
		}
		
		$result = $dsf->create($array['ds_key'], $array['ds_name'], $array['ds_description']);
		
		if($result !== NULL)
		{
			$this->go( Route::url('datasources', array(
				'directory' => 'hybrid',
				'controller' => 'section',
				'action' => 'edit',
				'id' => $result->id()
			)));
		}
		else
		{
			$this->go_back();
		}
	}

	public function action_edit()
	{
		$ds_id = (int) $this->request->param('id');

		if($this->request->method() === Request::POST)
		{
			return $this->_edit($this->section());
		}

		$this->breadcrumbs
			->add($this->ds->name, Route::url('datasources', array(
				'controller' => 'data',
				'directory' => 'datasources',
			)) . URL::query(array('ds_id' => $ds_id), FALSE))
			->add(__('Edit hybrid'));
		
		$this->template->content = View::factory('datasource/data/hybrid/edit', array(
			'record' => $this->section()->get_record(),
			'ds' => $this->section()
		));
	}
	
	private function _edit($ds)
	{
		$array = Validation::factory($this->request->post())
			->rules('ds_name', array(
				array('not_empty')
			))
			->label( 'ds_name', __('Header') );
		
		if(!$array->check())
		{
			Messages::errors($array->errors('validation'));
			$this->go_back();
		}
		
		$ds->name = $this->request->post('ds_name');
		$ds->description = $this->request->post('ds_description');
		$ds->doc_order = Arr::get($this->request->post(), 'doc_order', array());

		$ds->save();
		
		$headline_fields = $this->request->post('in_headline');
		if(!empty($headline_fields))
		{
			$fields = $this->section()->get_record()->fields;

			foreach($fields as $f)
			{
				$value = Arr::get($this->request->post('in_headline'), $f->id, 0);

				$field = DataSource_Hybrid_Field_Factory::get_field($f->id);
				$old_field = clone($field);

				$field->set(array(
					'in_headline' => $value
				));

				DataSource_Hybrid_Field_Factory::update_field($old_field, $field);
			}
		}

		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( Route::url('datasources', array(
				'directory' => 'datasources',
				'controller' => 'data'
			)) .  URL::query(array('ds_id' => $ds->id()), FALSE));
		}
		else
		{
			$this->go_back();
		}
	}
	
	public function action_remove()
	{
		$ds_id = (int) $this->request->param('id');
		$dsf = new DataSource_Hybrid_Factory();
		
		$dsf->remove($ds_id);
		$this->go_back();
	}
}