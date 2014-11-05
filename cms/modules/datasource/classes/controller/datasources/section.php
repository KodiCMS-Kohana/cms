<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Datasource
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Datasources_Section extends Controller_System_Datasource
{	
	public function before()
	{
		if ($this->request->action() != 'create')
		{
			$ds_id = (int) $this->request->param('id');
			$this->section($ds_id);

			if ($this->section()->has_access_edit())
			{
				$this->allowed_actions[] = 'edit';
			}

			if ($this->section()->has_access_remove())
			{
				$this->allowed_actions[] = 'remove';
			}
		}
		else
		{
			$type = strtolower($this->request->param('id'));
			if(ACL::check($type . '.section.create'))
			{
				$this->allowed_actions[] = 'create';
			}
		}

		parent::before();
	}

	public function action_create()
	{
		$type = $this->request->param('id');
		$type = strtolower($type);

		$types = Datasource_Data_Manager::types();
		if( Arr::get($types, $type) === NULL)
		{
			throw new Kohana_Exception('Datasource type :type not found', array(':type' => $type));
		}
		
		if($this->request->method() === Request::POST)
		{
			return $this->_create($type);
		}
		
		$this->set_title(__('Add section :type', array(':type' => Arr::get($types, $type))));
		
		try
		{
			$this->template->content = View::factory('datasource/'.$type.'/section/create', array(
				'type' => $type,
				'data' => Flash::get('post_data'),
				'users' => ORM::factory('user')->find_all()->as_array('id', 'username')
			));
		} 
		catch (Exception $exc)
		{
			$this->template->content = View::factory('datasource/section/create', array(
				'type' => $type,
				'data' => Flash::get('post_data'),
				'users' => ORM::factory('user')->find_all()->as_array('id', 'username')
			));
		}
	}
	
	/**
	 * 
	 * @param string $type
	 */
	private function _create($type)
	{
		$section = Datasource_Section::factory($type);
		
		$data = $this->request->post();
		
		$data['created_by_id'] = Auth::get_id();

		try
		{
			$ds_id = $section
				->validate($data)
				->create($data);
		}
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		catch (DataSource_Exception_Section $e)
		{
			Messages::errors($e->getMessage());
			$this->go_back();
		}
		
		Messages::success( __( 'Datasource has been saved!' ) );

		$this->go(Route::get('datasources')->uri(array(
			'directory' => 'datasources',
			'controller' => 'section',
			'action' => 'edit',
			'id' => $ds_id
		)));
	}

	public function action_edit()
	{
		if($this->request->method() === Request::POST)
		{
			return $this->_edit($this->section());
		}
		
		$this->breadcrumbs
			->add($this->section()->name, Route::get('datasources')->uri(array(
				'controller' => 'data',
				'directory' => 'datasources',
			)) . URL::query(array('ds_id' => $this->section()->id()), FALSE));
		
		$this->set_title(__('Edit section :name', array(
			':name' => $this->section()->name
		)));
		
		$this->template_js_params['DS_ID'] = $this->section()->id();
		$this->template_js_params['DS_TYPE'] = $this->section()->type();
			
		try
		{
			$this->template->content = View::factory('datasource/'.$this->section()->type().'/section/edit', array(
				'ds' => $this->section(),
				'users' => ORM::factory('user')->find_all()->as_array('id', 'username')
			));
		} 
		catch (Exception $exc)
		{
			$this->template->content = View::factory('datasource/section/edit', array(
				'ds' => $this->section(),
				'users' => ORM::factory('user')->find_all()->as_array('id', 'username')
			));
		}
	}
	
	/**
	 * 
	 * @param Datasource_Section $ds
	 */
	private function _edit($ds)
	{
		$data = $this->request->post();
		
		try
		{
			$ds->values($data);
			$ds->update();
		}
		catch (Validation_Exception $e)
		{
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}
		catch (DataSource_Exception_Section $e)
		{
			Messages::errors($e->getMessage());
			$this->go_back();
		}

		Messages::success( __( 'Datasource has been saved!' ) );

		// save and quit or save and continue editing?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( Route::get('datasources')->uri(array(
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
		$this->section()->remove();

		Messages::success(__('Datasource has been deleted!'));
		$this->go_back();
	}
}