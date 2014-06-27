<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Widgets extends Controller_System_Backend {

	public $allowed_actions = array('template');

	public function before()
	{
		parent::before();
		
		Assets::css('widgets', ADMIN_RESOURCES . 'css/widgets.css');
		
		$this->breadcrumbs
			->add(__('Widgets'), Route::get('backend')->uri(array(
				'controller' => 'widgets'
			)));
	}

	public function action_index()
	{
		$this->template->title = __('Widgets');
		
		$widgets = ORM::factory('widget')->filter();
	
		$per_page = (int) Arr::get($this->request->query(), 'per_page', 20);
		$pager = Pagination::factory(array(
			'total_items' => $widgets->reset(FALSE)->count_all(),
			'items_per_page' => $per_page
		));
		
		$sidebar = new Sidebar(array(
			new Sidebar_Fields_Select(array(
				'name' => 'widget_type[]',
				'label' => __('Type'),
				'options' => Widget_Manager::map(),
				'selected' => (array) $this->request->query('widget_type')
			)),
			new Sidebar_Fields_Input(array(
				'name' => 'per_page',
				'label' => __('Items per page'),
				'value' => $per_page,
				'class' => 'input-mini'
			))
		));

		$this->template->content = View::factory( 'widgets/index', array(
			'widgets' => $widgets
				->limit($pager->items_per_page)
				->offset($pager->offset)
				->find_all(),
			'pager' => $pager,
			'sidebar' => $sidebar
		));
	}
	
	public function action_location()
	{
		$id = $this->request->param('id');
		$widget = ORM::factory('widget', $id);

		if ( ! $widget->loaded() )
		{
			Messages::errors(__( 'Widget not found!' ) );
			$this->go_back();
		}
		
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add_location($widget);
		}
		
		$this->template->title = __('Widget :name location', array(
			':name' => $widget->name
		));
		
		$this->breadcrumbs
			->add(__('Widget :name', array(
				':name' => $widget->name)
				), 
				Route::get('backend')->uri(array(
					'controller' => 'widgets',
					'action' => 'edit',
					'id' => $widget->id
				))
			)->add(__('Widget location'));
		
		$res_page_widgets = DB::select()
			->from('page_widgets')
			->execute()
			->as_array();
		
		$pages_widgets = array(); // занятые блоки для исключения из списков
		$page_widgets = array(); // выбранные блоки для текущего виджета
		
		foreach ($res_page_widgets as $w)
		{
			if($w['widget_id'] == $widget->id)
				$page_widgets[$w['page_id']] = array($w['block'], $w['position']);
			else
				$pages_widgets[$w['page_id']][$w['block']] = array($w['block'], $w['position']);
		}

		$pages = Model_Page_Sitemap::get( TRUE )->as_array();
		
		$this->template->content = View::factory( 'widgets/location', array(
			'widget' => $widget,
			'pages' => $pages,
			'page_widgets' => $page_widgets,
			'pages_widgets' => $pages_widgets,
			'layouts_blocks' => Widget_Manager::get_blocks_by_layout()
		));
	}

	protected function _add_location( $widget )
	{
		$data = $this->request->post();
		Widget_Manager::set_location($widget->id, Arr::get($data, 'blocks', array()));
		$this->go_back();
	}

	public function action_add()
	{
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add();
		}

		$this->template->title = __('Create widget');
		
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'widgets/add', array(
			'types' => Widget_Manager::map()
		));
	}
	
	protected function _add()
	{
		$data = $this->request->post();
		$widget = Widget_Manager::factory( $data['type'] );
		
		try 
		{
			$widget->name = $data['name'];
			$widget->description = Arr::get($data, 'description');
	
			$id = Widget_Manager::create($widget);
			
			Observer::notify( 'widget_after_add', $id );
		}
		catch (ORM_Validation_Exception $e)
		{
			Flash::set( 'post_data', $data );
			Messages::errors($e->errors('validation'));
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
				'controller' => 'widgets',
				'action' => 'edit',
				'id' => $id
			));
		}
	}
	
	public function action_edit( )
	{
		$id = $this->request->param('id');

		$widget = Widget_Manager::load( $id );

		if ( ! $widget )
		{
			Messages::errors(__( 'Widget not found!' ) );
			$this->go_back();
		}
		
		$this->template->title = $widget->name;
		$this->breadcrumbs
			->add($widget->name);

		// check if trying to save
		if (Request::current()->method() == Request::POST)
		{
			return $this->_edit( $widget );
		}
		
		$roles = ORM::factory('role')->find_all()->as_array('name', 'name');

		$this->template->content = View::factory( 'widgets/edit', array(
			'widget' => $widget,
			'templates' => Model_File_Snippet::html_select(),
			'content' =>  $widget->fetch_backend_content(),
			'roles' => $roles,
		) );
	}
	
	protected function _edit( Model_Widget_Decorator $widget )
	{
		$data = $this->request->post();
		
		try 
		{
			if ( ! ACL::check('widget.roles') AND ! empty($data['roles']))
			{
				$data['roles'] = array();
			}
			
			if(ACL::check('widgets.cache'))
			{
				$widget->set_cache_settings( $data );
			}

			$widget
				->set_values( $data );
			
			Widget_Manager::update($widget);
			
			Observer::notify('widget_after_edit', $widget->id);
		}
		catch (Validation_Exception $e)
		{
			Flash::set('post_data', $data);
			Messages::errors($e->errors('validation'));
			$this->go_back();
		}

		// save and quit or save and continue editing?
		if ($this->request->post('commit') !== NULL)
		{
			$this->go();
		}
		else
		{
			$this->go(array(
				'controller' => 'widgets',
				'action' => 'edit',
				'id' => $widget->id
			));
		}
	}
	
	public function action_delete()
	{
		$id = $this->request->param('id');
		
		Widget_Manager::remove(array($id));
		
		Observer::notify( 'widget_after_delete', $id );
		$this->go_back();
	}
	
	public function action_template()
	{
		$id = (int) $this->request->param('id');
		
		$widget = Widget_Manager::load( $id );

		if ( ! $widget )
		{
			Messages::errors(__( 'Widget not found!' ) );
			$this->go_back();
		}
		
		Assets::package('ace');
		
		$template = $widget->default_template();
		
		$data = file_get_contents( $template );
		$this->template->content = View::factory('widgets/default_template', array(
			'data' => $data
		));
	}
}