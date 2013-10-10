<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Widgets extends Controller_System_Backend {

	public $auth_required = array( 'administrator', 'developer' );

	public function before()
	{
		parent::before();
		
		Assets::css('widgets', ADMIN_RESOURCES . 'css/widgets.css');
		
		$this->breadcrumbs
			->add(__('Widgets'), Route::url('backend', array(
				'controller' => 'widgets'
			)));
	}

	public function action_index()
	{
		$this->template->title = __('Widgets');

		$this->template->content = View::factory( 'widgets/index', array(
			'widgets' => ORM::factory('widget')->find_all()
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
				Route::url('backend', array(
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
				$page_widgets[$w['page_id']] = $w['block'];
			else
				$pages_widgets[$w['page_id']][$w['block']] = $w['block'];
		}

		$pages = Model_Page_Sitemap::get()->as_array();
		
		$res_blocks = ORM::factory('layout_block')->find_all();
		
		$blocks = array();
		
		$default_blocks = array('----', 'PRE' => __('Before page render'), 'POST' => __('After page render'));
		foreach ($res_blocks as $block)
		{
			if(empty($blocks[$block->layout_name])) 
				$blocks[$block->layout_name] = $default_blocks;

			$blocks[$block->layout_name][$block->block] = $block->block;
		}
		
		$this->template->content = View::factory( 'widgets/location', array(
			'widget' => $widget,
			'pages' => $pages,
			'page_widgets' => $page_widgets,
			'pages_widgets' => $pages_widgets,
			'blocks' => $blocks,
			'default_blocks' => $default_blocks
		));
	}
	
	protected function _add_location( $widget )
	{
		$data = $this->request->post();
		
		DB::delete('page_widgets')
			->where('widget_id', '=', $widget->id)
			->execute();
		
		if(!empty($data))
		{
			$insert = DB::insert('page_widgets')
				->columns(array('page_id', 'widget_id', 'block'));

			$i = 0;
			foreach($data['blocks'] as $page_id => $block)
			{
				if(empty($block)) continue;

				$insert->values(array(
					$page_id, $widget->id, $block
				));
				$i++;
			}
			
			if( $i > 0 ) $insert->execute();
		}
		
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
			'types' => Kohana::$config->load('widgets')->as_array()
		));
	}
	
	protected function _add()
	{
		$data = $this->request->post();
		$widget = Widget_Manager::get_empty_object( $data['type'] );
		
		try 
		{
			$widget->name = $data['name'];
			$widget->description = Arr::get($data, 'description');
	
			$id = Widget_Manager::create($widget);
		}
		catch (Validation_Exception $e)
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
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $widget );
		}
		
		$templates = array(
			__('None')
		);
		$snippets = Model_File_Snippet::find_all();
		
		foreach ($snippets as $snippet)
		{
			$templates[$snippet->name] = $snippet->name;
		}

		$this->template->content = View::factory( 'widgets/edit', array(
			'widget' => $widget,
			'templates' => $templates,
			'content' =>  $widget->fetch_backend_content()
		) );
	}
	
	protected function _edit( Model_Widget_Decorator $widget )
	{
		$data = $this->request->post();
		
		try 
		{
			$widget
				->set_values( $data );
			
			if( ACL::check( 'widgets.cache'))
			{
				$widget->set_cache_settings( $data );
			}
			
			Widget_Manager::update($widget);
		}
		catch (Validation_Exception $e)
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
				'id' => $widget->id
			));
		}
	}
	
	public function action_delete()
	{
		$id = $this->request->param('id');
		
		Widget_Manager::remove(array($id));
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
		
		$template = $widget->default_template();
		
		$data = file_get_contents( $template );
		$this->template->content = View::factory('widgets/default_template', array(
			'data' => $data
		));
	}
}