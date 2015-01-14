<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Hybrid
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Hybrid_Field extends Controller_System_Datasource
{
	public $field = NULL;
	
	public function before()
	{
		if ($this->request->action() == 'edit')
		{
			$id = (int) $this->request->param('id');
			$this->field = DataSource_Hybrid_Field_Factory::get_field($id);

			if (empty($this->field))
			{
				throw new HTTP_Exception_404('Field ID :id not found', array(':id' => $id));
			}

			$ds = $this->section($this->field->ds_id);

			if ($this->field->has_access_edit())
			{
				$this->allowed_actions[] = 'edit';
			}
		}

		if ($this->request->action() == 'add')
		{
			$ds_id = (int) $this->request->param('id');
			$ds = $this->section($ds_id);

			if ($ds->has_access('field.create'))
			{
				$this->allowed_actions[] = 'add';
			}
		}

		parent::before();

		if (!empty($ds))
		{
			$this->template_js_params['DS_ID'] = $ds->id();
		}
	}
	
	public function action_template()
	{
		$ds_id = (int) $this->request->param('id');
		$ds = $this->section($ds_id);

		$this->template->content = View::factory('datasource/hybrid/field/template', array(
			'ds' => $ds,
			'fields' => $ds->record()->fields
		));
	}

	public function action_add()
	{
		$ds_id = (int) $this->request->param('id');
		$ds = $this->section($ds_id);
		
		if ($this->request->method() === Request::POST)
		{
			return $this->_add($ds);
		}

		$this->set_title(__('Add field'));
		
		$this->breadcrumbs
			->add($ds->name, Route::get('datasources')->uri(array(
				'controller' => 'data',
				'directory' => 'datasources',
			)) . URL::query(array('ds_id' => $ds->id()), FALSE))
			->add(__('Edit section :name', array(':name' => $ds->name)), Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'section',
				'action' => 'edit',
				'id' => $ds->id()
			)));
		
		$this->template->content = View::factory('datasource/hybrid/field/add', array(
			'ds' => $ds,
			'sections' => $this->_get_sections(),
			'post_data' => Session::instance()->get_once('post_data', array())
		));
	}
	
	private function _add($ds)
	{
		$data = $this->request->post();
		
		try 
		{
			$type = $data['type'];
			unset($data['type']);
			
			$field = DataSource_Hybrid_Field::factory($type, $data);
			$field_id = DataSource_Hybrid_Field_Factory::create_field($ds->record(), $field);
		}
		catch (Validation_Exception $e)
		{
			Session::instance()->set('post_data', $this->request->post());
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		catch (Kohana_Exception $e)
		{
			Messages::errors($e->getMessage());
			$this->go_back();
		}
		
		if (!$field_id)
		{
			$this->go_back();
		}

		Session::instance()->delete('post_data');
		
		if ($this->request->post('save_and_create') !== NULL)
		{
			$this->go(Route::get('datasources')->uri(array(
				'controller' => 'field',
				'directory' => 'hybrid',
				'action' => 'add',
				'id' => $ds->id()
			)));
		}
		else
		{
			$this->go(Route::get('datasources')->uri(array(
				'directory' => 'hybrid',
				'controller' => 'field',
				'action' => 'edit',
				'id' => $field_id
			)));
		}
	}

	public function action_edit()
	{
		$ds = $this->section($this->field->ds_id);

		if ($this->request->method() === Request::POST)
		{
			return $this->_edit($this->field);
		}

		$this->set_title(__('Edit field :field_name', array(':field_name' => $this->field->header)));

		$this->breadcrumbs
			->add($ds->name, Route::get('datasources')->uri(array(
				'controller' => 'data',
				'directory' => 'datasources',
			)) . URL::query(array('ds_id' => $ds->id()), FALSE))
			->add(__('Edit section :name', array(':name' => $ds->name)), Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'section',
				'action' => 'edit',
				'id' => $ds->id()
			)));

		$this->template->content = View::factory('datasource/hybrid/field/edit', array(
			'ds' => $ds,
			'field' => $this->field,
			'type' => $this->field->type,
			'sections' => $this->_get_sections(),
			'post_data' => Session::instance()->get_once('post_data', array())
		));
	}
	
	private function _edit($field)
	{
		try
		{
			$field->set($this->request->post());
			DataSource_Hybrid_Field_Factory::update_field(clone($field), $field);
		}
		catch (Validation_Exception $e)
		{
			Session::instance()->set('post_data', $this->request->post());
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		catch (Kohana_Exception $e)
		{
			Messages::errors($e->getMessage());
			$this->go_back();
		}
		
		Session::instance()->delete('post_data');
		
		// save and quit or save and continue editing?
		if ($this->request->post('commit') !== NULL)
		{
			$this->go(Route::get('datasources')->uri(array(
				'directory' => 'datasources',
				'controller' => 'section',
				'action' => 'edit',
				'id' => $field->ds_id
			)));
		}
		else
		{
			$this->go_back();
		}
	}
	
	protected function _get_sections()
	{
		$map = Datasource_Data_Manager::get_tree();
		$hds = Datasource_Data_Manager::get_all('hybrid');

		$sections = array();

		foreach (Datasource_Data_Manager::types() as $key => $value)
		{
			if ($key != 'hybrid' AND ! empty($map[$key]))
			{
				foreach ($map[$key] as $id => $section)
				{
					$sections[$key][$id] = $section->name;
				}
			}
			else
			{
				foreach ($hds as $id => $section)
				{
					$sections[$key][$id] = $section->name;
				}
			}
		}

		return $sections;
	}
}