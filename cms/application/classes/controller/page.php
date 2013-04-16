<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Page extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();
		$this->breadcrumbs
			->add(__('Pages'), $this->request->controller());
		
		$this->scripts[] = ADMIN_RESOURCES . 'js/controller/page.js';
		$this->scripts[] = ADMIN_RESOURCES . 'js/controller/behavior.js';
	}

	public function action_index()
	{
		$this->template->title = __('Pages');

		$this->template->content = View::factory( 'page/index', array(
			'page' => Model_Page::findById( 1 ),
			'content_children' => $this->children( 1, 0, true )
		) );
	}

	public function action_add( )
	{
		$this->scripts[] = ADMIN_RESOURCES . 'js/controller/parts.js';
		$parent_id = (int) $this->request->param('id', 1);

		// check if trying to save
		if ( $this->request->method() == Request::POST )
		{
			return $this->_add( $parent_id );
		}

		$data = Flash::get( 'post_data' );
		$page = new Model_Page( $data );
		$page->parent_id = $parent_id;
		$page->status_id = Setting::get( 'default_status_id' );
		$page->needs_login = Model_Page::LOGIN_INHERIT;
		$page->published_on = date( 'Y-m-d H:i:s' );
		
		
		$this->template->title = __('Add page');
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'page/edit', array(
			'action' => 'add',
			'parent_id' => $parent_id,
			'page' => $page,
			'pages' => Model_Page_Sitemap::get()->exclude(array($page->id))->flatten(),
			'tags' => Flash::get('page_tag', array()),
			'filters' => Filter::findAll(),
			'behaviors' => Behavior::findAll(),
			'layouts' => Model_File_Layout::find_all(),
			'permissions' => Model_Permission::get_all(),
			'page_permissions' => $page->get_permissions()
		) );
	}

	private function _add( $parent_id )
	{
		$data = $this->request->post('page');
		$tags = Arr::get($data, 'tags', array());

		Flash::set( 'post_data', (object) $data );
		Flash::set( 'page_tag', $tags );

		if ( Setting::get( 'allow_html_title' ) == 'off' )
		{
			$data['title'] = Kses::filter( trim( $data['title'] ), array( ) );
		}

		if ( !AuthUser::hasPermission( array( 'administrator', 'developer' ) ) )
		{
			$data['status_id'] = Setting::get( 'default_status_id' );
		}

		$page = new Model_Page( $data, array('tags') );
		$page->parent_id = $parent_id;
		
		Observer::notify( 'page_add_before_save', $page );

		// save page data
		if ( $page->save() )
		{
			// save tags
			$page->save_tags( $tags );

			// save permissions
			$permissions = $this->request->post('page_permissions');
			if(empty($permissions))
			{
				$permissions = array( 'administrator', 'developer', 'editor' );
			}
			
			$page->save_permissions( $permissions );

			Observer::notify( 'page_add_after_save', $page);

			Messages::success( __( 'Page has been saved!' ) );
		}
		else
		{
			Messages::errors( __( 'Page has not been saved!' ) );
			$this->go( 'page/add/' . $parent_id );
		}
		
		Session::instance()->delete('post_data', 'post_parts_data', 'page_tag');

		// save and quit or save and continue editing ?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( 'page' );
		}
		else
		{
			$this->go( 'page/edit/' . $page->id );
		}
	}

	public function action_edit( )
	{
		$this->scripts[] = ADMIN_RESOURCES . 'js/controller/parts.js';

		$page_id = $this->request->param('id');

		$page = Model_Page::findById( $page_id );

		if ( ! $page )
		{
			Messages::errors( __( 'Page not found!' ) );
			$this->go( 'page' );
		}

		// check for protected page and editor user
		if ( !AuthUser::hasPermission( $page->get_permissions() ) )
		{
			// Unauthorized / Login Requied
			Messages::errors( __( 'You do not have permission to access the requested page!' ) );
			$this->go( 'page' );
		}

		// check if trying to save
		if ( $this->request->method() == Request::POST )
		{
			return $this->_edit( $page_id );
		}
		
		$this->template->title = $page->title;
		$this->breadcrumbs
			->add($this->template->title);

		$this->template->content = View::factory( 'page/edit', array(
			'action' => 'edit',
			'page' => $page,
			'pages' => Model_Page_Sitemap::get()->exclude(array($page->id))->flatten(),
			'tags' => Flash::get('page_tag', $page->get_tags()),
			'filters' => Filter::findAll(),
			'behaviors' => Behavior::findAll(),
			'layouts' => Model_File_Layout::find_all(),
			'permissions' => Model_Permission::get_all(),
			'page_permissions' => $page->get_permissions()
		) );
	}

	private function _edit( $page_id )
	{
		$data = $this->request->post('page');

		if ( Setting::get( 'allow_html_title' ) == 'off' )
		{
			$data['title'] = Kses::filter( trim( $data['title'] ), array( ) );
		}

		if ( isset( $data['status_id'] ) && !AuthUser::hasPermission( array( 'administrator', 'developer' ) ) )
		{
			unset( $data['status_id'] );
		}

		$page = Record::findByIdFrom( 'Model_Page', $page_id );

		$page->setFromData( $data, array( 'tags' ) );

		Observer::notify( 'page_edit_before_save', $page );

		if ( $page->save() )
		{
			// save parts
			foreach (Arr::get($this->request->post(), 'part_content', array()) as $id => $content)
			{
				$part = Record::findByIdFrom('Model_Page_Part', (int) $id);
		
				$part
					->setFromData(array('content' => $content))
					->save();
			}

			// save tags
			$page->save_tags(Arr::get($data, 'tags', array()) );

			if( AuthUser::hasPermission( 'administrator, developer' ) )
			{
				// save permissions
				$permissions = $this->request->post('page_permissions');
				$page->save_permissions( $permissions );
			}

			Observer::notify( 'page_edit_after_save', $page );

			Messages::success( __( 'Page has been saved!' ) );
		}
		else
		{
			Messages::errors( __( 'Something went wrong!' ) );
			$this->go( 'page/edit/' . $page_id );
		}

		// save and quit or save and continue editing ?
		if ( $this->request->post('commit') !== NULL )
		{
			$this->go( 'page' );
		}
		else
		{
			$this->go( 'page/edit/' . $page_id );
		}
	}

	/**
	 * Used to delete a page.
	 * 
	 * TODO - make sure we not only delete the page but also all parts and all children!
	 *
	 * @param int $id Id of page to delete
	 */
	public function action_delete( )
	{
		$this->auto_render = FALSE;
		$page_id = $this->request->param('id');

		// security (dont delete the SYSPATH page)
		if ( $page_id > 1 )
		{
			// find the page to delete
			if ( $page = Record::findByIdFrom( 'Model_Page', $page_id ) )
			{
				// check for permission to delete this page
				if ( !AuthUser::hasPermission( $page->get_permissions() ) )
				{
					Messages::errors( __( 'You do not have permission to access the requested page!' ) );
					$this->go( 'page' );
				}

				if ( $page->delete() )
				{
					Observer::notify( 'page_delete', $page );
					Messages::success( __( 'Page has been deleted!' ) );
				}
				else
				{
					Messages::errors( __( 'Something went wrong!' ) );
				}
			}
			else
			{
				Messages::errors( __( 'Page not found!' ) );
			}
		}
		else
		{
			Messages::errors( __( 'Something went wrong!' ) );
		}

		$this->go( 'page' );
	}
	
	
	public function children( $parent_id, $level, $return = FALSE )
	{
		$expanded_rows = isset( $_COOKIE['expanded_rows'] ) ? explode( ',', $_COOKIE['expanded_rows'] ) : array( );

		$page = Model_Page::findById($parent_id);
		$behavior = Behavior::get( $page->behavior_id );
		
		$clause = array();
		
		if( ! empty($behavior['limit']))
		{
			$clause['limit'] = (int) $behavior['limit'];
		}
			
		// get all children of the page (parent_id)
		$childrens = Model_Page::childrenOf( $parent_id, $clause );

		foreach ( $childrens as $index => $child )
		{
			$childrens[$index]->has_children = Model_Page::hasChildren( $child->id );
			
			$child_behavior = Behavior::get( $child->behavior_id );
			
			if( ! empty($child_behavior['link']))
			{
				$childrens[$index]->has_children = TRUE;
			}
			
			$childrens[$index]->is_expanded = in_array( $child->id, $expanded_rows );
			//$childrens[$index]->is_expanded = true;

			if ( $childrens[$index]->is_expanded )
			{
				$childrens[$index]->children_rows = $this->children( $child->id, $level + 1, true );
			}
		}
		
		if( ! empty($behavior['limit']) )
		{
			$childrens[] = '...';
		}
		
		if( ! empty($behavior['link']))
		{
			$link = strtr($behavior['link'], array(':id' => $parent_id));
			$childrens[] = __(':icon :link', array(
				':icon' => UI::icon('book'),
				':link' => HTML::anchor( $link, __(ucfirst($page->behavior_id)))
			));
		}

		$content = new View( 'page/children', array(
			'childrens' => $childrens,
			'level' => $level + 1,
		) );

		if ( $return )
		{
			return $content;
		}

		echo $content;
	}
	
	
	

	public function action_children( )
	{
		$this->auto_render = FALSE;

		$parent_id = $this->request->query('parent_id');
		$level = $this->request->query('level');
		
		return $this->children($parent_id, $level);
	}

	/**
	 * Ajax action to reorder (page->position) a page
	 *
	 * all the child of the new page->parent_id have to be updated
	 * and all nested tree has to be rebuild
	 */
	public function action_reorder( )
	{
		$this->auto_render = FALSE;
		$parent_id = $this->request->post('parent_id');

		$pages = $this->request->post('pages');
		if ( $pages )
		{
			foreach ( $pages as $position => $page_id )
			{
				$page = Record::findByIdFrom( 'Model_Page', $page_id );
				$page->position = (int) $position;
				$page->parent_id = (int) $parent_id;
				$page->save();
			}
		}
	}

	public function action_search()
	{
		$this->auto_render = FALSE;

		$query = trim( $this->request->post('search') );

		$childrens = array( );

		if ( $query == '*' )
		{
			$childrens = Model_Page::findAll();
		}
		else if ( strlen( $query ) == 2 AND $query[0] == '.' )
		{
			$page_status = array(
				'd' => Model_Page::STATUS_DRAFT,
				'r' => Model_Page::STATUS_REVIEWED,
				'p' => Model_Page::STATUS_PUBLISHED,
				'h' => Model_Page::STATUS_HIDDEN
			);

			if ( isset( $page_status[$query[1]] ) )
			{
				$childrens = Model_Page::find( array( 
					'where' => array(
						array('page.status_id', '=', $page_status[$query[1]])
					)));
			}
		}
		else if ( substr( $query, 0, 1 ) == '-' )
		{
			$query = trim( substr( $query, 1 ) );
			
			$subreqest = DB::select('p.id')
				->from(array(Model_Page::tableName(), 'p'))
				->where('p.slug', '=', $query)
				->limit(1);
			$childrens = Model_Page::find( array( 
				'where' => array(array('page.parent_id', '=', $subreqest))
			));
		}
		else
		{
			$childrens = Model_Page::findAllLike( $query );
		}

		foreach ( $childrens as $index => $child )
		{
			$childrens[$index]->is_expanded = false;
			$childrens[$index]->has_children = false;
		}

		echo View::factory( 'page/children', array(
			'childrens' => $childrens,
			'level' => 0
		) );
	}

	private function _getPartView( $index = 1, $name = '', $filter_id = '', $content = '' )
	{
		$page_part = new Model_Page_Part( array(
			'name' => $name,
			'filter_id' => $filter_id,
			'content' => $content
		) );

		echo View::factory('page/blocks/part_edit', array(
			'index' => $index,
			'page_part' => $page_part
		) );
	}
}
// end PageController class