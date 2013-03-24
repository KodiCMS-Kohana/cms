<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Widgets extends Controller_System_Backend {

	public $auth_required = array( 'administrator', 'developer' );

	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Widgets'), $this->request->controller());
	}

	public function action_index()
	{
		$this->template->title = __('Widgets');
		$this->template->content = View::factory( 'widgets/index');
		
		$res_widgets = ORM::factory('widget')
			->find_all();
		
		$this->template->content = View::factory( 'widgets/index', array(
			'widgets' => $res_widgets
		));
	}
	
	public function action_location()
	{
		$id = $this->request->param('id');
		$widget = ORM::factory('widget', $id);

		if ( ! $widget->loaded() )
		{
			Messages::errors(__( 'Widget not found!' ) );
			$this->go( 'widgets' );
		}
		
		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add_location($widget);
		}		
		
		$this->breadcrumbs
			->add(__('Widget :name', array(':name' => $widget->name)), 'widgets/edit/' . $widget->id)
			->add(__('Widget location'));
		
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
		foreach ($res_blocks as $block)
		{
			if(empty($blocks[$block->layout_name])) 
				$blocks[$block->layout_name] = array(
					'----', 'PRE' => __('Before page render')
				);

			$blocks[$block->layout_name][$block->block] = $block->block;
		}
		
		$this->template->content = View::factory( 'widgets/location', array(
			'widget' => $widget,
			'pages' => $pages,
			'page_widgets' => $page_widgets,
			'pages_widgets' => $pages_widgets,
			'blocks' => $blocks
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
			$this->go( 'widgets' );
		}
		else
		{
			$this->go( 'widgets/edit/' . $id );
		}
	}
	
	public function action_edit( )
	{
		$id = $this->request->param('id');

		$widget = Widget_Manager::load( $id );

		if ( ! $widget )
		{
			Messages::errors(__( 'Widget not found!' ) );
			$this->go( 'widgets' );
		}
		
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
		
		// Если не создать View шаблон, не загружаем его
		try
		{
			$content = View::factory( 'widgets/widget/' . $widget->type, array(
						'widget' => $widget
				))->set($widget->load_template_data());
		}
		catch( Kohana_Exception $e)
		{
			$content = NULL;
		}

		$this->template->content = View::factory( 'widgets/edit', array(
			'widget' => $widget,
			'templates' => $templates,
			'content' =>  $content
		) );
	}
	
	protected function _edit( Model_Widget_Decorator $widget )
	{
		$data = $this->request->post();
		
		try 
		{
			$widget
				->set_values( $data )
				->set_cache_settings( $data );
			
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
			$this->go( 'widgets' );
		}
		else
		{
			$this->go( 'widgets/edit/' . $widget->id );
		}
	}
}