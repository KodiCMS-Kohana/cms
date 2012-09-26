<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Page extends Controller_System_Backend {

	public function action_index()
	{
		$this->template->content = View::factory( 'page/index', array(
			'page' => Record::findByIdFrom( 'Page', 1 ),
			'content_children' => $this->children( 1, 0, true )
		) );
	}

	public function action_add( )
	{
		$parent_id = $this->request->param('id', 1);

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_add( $parent_id );
		}

		$data = Flash::get( 'post_data' );
		$page = new Page( $data );
		$page->parent_id = $parent_id;
		$page->status_id = Setting::get( 'default_status_id' );
		$page->needs_login = Page::LOGIN_INHERIT;
		$page->published_on = date( 'Y-m-d H:i:s' );
		
		
		$this->template->breadcrumbs = array(
			HTML::anchor( 'page', __('Pages')),
			__('Add page')
		);

		$page_parts = Flash::get( 'post_parts_data' );

		if ( empty( $page_parts ) )
		{
			// check if we have a big sister ...
			$big_sister = Record::findOneFrom( 'Page', 'parent_id = :parent_id ORDER BY id DESC', array(':parent_id' =>  $parent_id ) );
			if ( $big_sister )
			{
				// get all is part and create the same for the new little sister
				$big_sister_parts = Record::findAllFrom( 'PagePart', 'page_id = :page_id ORDER BY id', array( ':page_id' => $big_sister->id ) );
				$page_parts = array( );
				foreach ( $big_sister_parts as $parts )
				{
					$page_parts[] = new PagePart( array(
						'name' => $parts->name,
						'filter_id' => Setting::get( 'default_filter_id' ),
						'is_protected' => $parts->is_protected
					) );
				}
			}
			else
				$page_parts = array( new PagePart( array( 'filter_id' => Setting::get( 'default_filter_id' ), 'is_protected' => false ) ) );
		}

		$this->template->content = View::factory( 'page/edit', array(
			'action' => 'add',
			'parent_id' => $parent_id,
			'page' => $page,
			'tags' => array( ),
			'filters' => Filter::findAll(),
			'behaviors' => Behavior::findAll(),
			'page_parts' => $page_parts,
			'layouts' => Model_File_Layout::find_all(),
			'permissions' => Record::findAllFrom( 'Permission' ),
			'page_permissions' => $page->getPermissions()
		) );
	}

	private function _add( $parent_id )
	{
		$data = $_POST['page'];
		$tags = Arr::get($data, 'tags', array());
		$parts = Arr::get($_POST, 'part', array());

		Flash::set( 'post_data', (object) $data );
		Flash::set( 'post_parts_data', (object) $parts );
		Flash::set( 'page_tag', $tags );

		if ( empty( $data['title'] ) )
		{
			Messages::errors( __( 'You have to specify a title!' ) );
			$this->go( URL::site( 'page/add/' . $parent_id ) );
		}

		/**
		 * Make sure the title doesn't contain HTML
		 * 
		 * @todo Replace this by HTML Purifier?
		 * @todo HTML Purifier is too big. What about another? Jevix?
		 */
		if ( Setting::get( 'allow_html_title' ) == 'off' )
		{
			$data['title'] = Kses::filter( trim( $data['title'] ), array( ) );
		}

		if ( !AuthUser::hasPermission( array( 'administrator', 'developer' ) ) )
		{
			$data['status_id'] = Setting::get( 'default_status_id' );
		}

		$page = new Page( $data, array('tags') );
		$page->parent_id = $parent_id;

		// save page data
		if ( $page->save() )
		{
			foreach ( $parts as $data_part )
			{
				$data_part['page_id'] = $page->id;
				$data_part['name'] = trim( $data_part['name'] );

				$page_part = new PagePart( $data_part );
				$page_part->save();
			}

			// save tags
			$page->saveTags( $tags );
			
			// save permissions
			$permissions = Arr::get($_POST, 'page_permissions', array( 'administrator', 'developer', 'editor' ));
			$page->savePermissions( $permissions );

			Observer::notify( 'page_add_after_save', array( $page ) );

			Messages::success( __( 'Page <b>:title</b> has been saved!', array( ':title' => $page->title ) ) );
		}
		else
		{
			Messages::errors( __( 'Page has not been saved!' ) );
			$this->go( URL::site( 'page/add/' . $parent_id ) );
		}
		
		Session::instance()->delete('post_data', 'post_parts_data', 'page_tag');

		// save and quit or save and continue editing ?
		if ( isset( $_POST['commit'] ) )
		{
			$this->go( URL::site( 'page' ) );
		}
		else
		{
			$this->go( URL::site( 'page/edit/' . $page->id ) );
		}
	}

	public function action_add_part()
	{
		$this->auto_render = FALSE;

		$data = isset( $_POST ) ? $_POST : array( );
		$data['name'] = isset( $data['name'] ) ? trim( $data['name'] ) : '';
		$data['index'] = isset( $data['index'] ) ? (int) $data['index'] : 1;

		echo $this->_getPartView( $data['index'], $data['name'], Setting::get( 'default_filter_id' ) );
	}

	public function action_edit( )
	{
		$page_id = $this->request->param('id');

		$page = Page::findById( $page_id );

		if ( !$page )
		{
			Messages::errors( __( 'Page not found!' ) );
			$this->go( URL::site( 'page' ) );
		}

		// check for protected page and editor user
		if ( !AuthUser::hasPermission( $page->getPermissions() ) )
		{
			Messages::errors( __( 'You do not have permission to access the requested page!' ) );
			$this->go( URL::site( 'page' ) );
		}

		// check if trying to save
		if ( Request::current()->method() == Request::POST )
		{
			return $this->_edit( $page_id );
		}

		// find all page_part of this pages
		$page_parts = PagePart::findByPageId( $page_id );

		if ( empty( $page_parts ) )
		{
			$page_parts = array( new PagePart );
		}
		
		$this->template->breadcrumbs = array(
			HTML::anchor( 'page', __('Pages')),
			__('Edit page ":page"', array(':page' => $page->title))
		);

		$this->template->content = View::factory( 'page/edit', array(
			'action' => 'edit',
			'page' => $page,
			'tags' => $page->getTags(),
			'filters' => Filter::findAll(),
			'behaviors' => Behavior::findAll(),
			'page_parts' => $page_parts,
			'layouts' => Model_File_Layout::find_all(),
			'permissions' => Record::findAllFrom( 'Permission' ),
			'page_permissions' => $page->getPermissions()
		) );
	}

	private function _edit( $page_id )
	{
		$data = $_POST['page'];

		/**
		 * Make sure the title doesn't contain HTML
		 * 
		 * @todo Replace this by HTML Purifier?

		 */
		if ( Setting::get( 'allow_html_title' ) == 'off' )
		{
			$data['title'] = Kses::filter( trim( $data['title'] ), array( ) );
		}

		if ( isset( $data['status_id'] ) && !AuthUser::hasPermission( array( 'administrator', 'developer' ) ) )
		{
			unset( $data['status_id'] );
		}

		$page = Record::findByIdFrom( 'Page', $page_id );

		$page->setFromData( $data, array( 'tags' ) );

		Observer::notify( 'page_edit_before_save', array( $page ) );

		if ( $page->save() )
		{
			// get data for parts of this page
			$data_parts = Arr::get($_POST, 'part', array());

			$old_parts = PagePart::findByPageId( $page_id );

			// check if all old page part are passed in POST
			// if not ... we need to delete it!
			foreach ( $old_parts as $old_part )
			{
				// check user rights if part is protected
				if ( $old_part->is_protected == PagePart::PART_PROTECTED && !AuthUser::hasPermission( array( 'administrator', 'developer' ) ) )
					continue;

				$not_in = true;
				foreach ( $data_parts as $part_id => $part_data )
				{
					$part_data['name'] = trim( $part_data['name'] );

					if ( $old_part->name == $part_data['name'] )
					{
						$not_in = false;

						// this will not really create a new page part because
						// the id of the part is passed in $data
						$part = new PagePart( $part_data );
						$part->page_id = $page_id;

						Observer::notify( 'part_edit_before_save', array( $part ) );

						$part->save();

						Observer::notify( 'part_edit_after_save', array( $part ) );

						unset( $data_parts[$part_id] );

						break;
					}
				}

				if ( $not_in )
				{
					$old_part->delete();
				}
			}

			// add the new ones
			foreach ( $data_parts as $part_id => $part_data )
			{
				$part_data['name'] = trim( $part_data['name'] );
				$part = new PagePart( $part_data );
				$part->page_id = $page_id;
				$part->save();
			}

			// save tags
			$page->saveTags(Arr::get($data, 'tags', array()) );

			// save permissions
			$permissions = Arr::get($_POST, 'page_permissions', array());
			$page->savePermissions( $permissions );

			Observer::notify( 'page_edit_after_save', array( $page ) );

			Messages::success( __( 'Page <b>:title</b> has been saved!', array( ':title' => $page->title ) ) );
		}
		else
		{
			Messages::errors( __( 'Page <b>:title</b> has not been saved!', array( ':title' => $page->title ) ) );
			$this->go( URL::site( 'page/edit/' . $page_id ) );
		}

		// save and quit or save and continue editing ?
		if ( isset( $_POST['commit'] ) )
		{
			$this->go( URL::site( 'page' ) );
		}
		else
		{
			$this->go( URL::site( 'page/edit/' . $page->id ) );
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
			if ( $page = Record::findByIdFrom( 'Page', $page_id ) )
			{
				// check for permission to delete this page
				if ( !AuthUser::hasPermission( $page->getPermissions() ) )
				{
					Messages::errors( __( 'You do not have permission to access the requested page!' ) );
					$this->go( URL::site( 'page' ) );
				}

				if ( $page->delete() )
				{
					// need to delete all page_parts too !!
					PagePart::deleteByPageId( $page_id );

					Observer::notify( 'page_delete', array( $page ) );
					Messages::success( __( 'Page <b>:title</b> has been deleted!', array( ':title' => $page->title ) ) );
				}
				else
				{
					Messages::errors( __( 'Page <b>:title</b> has not been deleted!', array( ':title' => $page->title ) ) );
				}
			}
			else
			{
				Messages::errors( __( 'Page not found!' ) );
			}
		}
		else
		{
			Messages::errors( __( 'Action disabled!' ) );
		}

		$this->go( URL::site( 'page' ) );
	}
	
	
	public function children( $parent_id, $level, $return = FALSE )
	{
		$expanded_rows = isset( $_COOKIE['expanded_rows'] ) ? explode( ',', $_COOKIE['expanded_rows'] ) : array( );

		// get all children of the page (parent_id)
		$childrens = Page::childrenOf( $parent_id );

		foreach ( $childrens as $index => $child )
		{
			$childrens[$index]->has_children = Page::hasChildren( $child->id );
			$childrens[$index]->is_expanded = in_array( $child->id, $expanded_rows );
			//$childrens[$index]->is_expanded = true;

			if ( $childrens[$index]->is_expanded )
			{
				$childrens[$index]->children_rows = $this->children( $child->id, $level + 1, true );
			}
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
		$parent_id = Arr::get($_GET, 'parent_id');
		$level = Arr::get($_GET, 'level');
		
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
		$parent_id = Arr::get($_POST, 'parent_id', 0);

		if ( !empty( $_POST['pages'] ) )
		{
			$pages = $_POST['pages'];

			foreach ( $pages as $position => $page_id )
			{
				$page = Record::findByIdFrom( 'Page', $page_id );
				$page->position = (int) $position;
				$page->parent_id = (int) $parent_id;
				$page->save();
			}
		}
	}

	public function action_search()
	{
		$this->auto_render = FALSE;

		$query = trim( $_POST['search'] );

		$childrens = array( );

		if ( $query == '*' )
		{
			$childrens = Page::findAll();
		}
		else if ( strlen( $query ) == 2 && $query[0] == '.' )
		{
			$page_status = array(
				'd' => Page::STATUS_DRAFT,
				'r' => Page::STATUS_REVIEWED,
				'p' => Page::STATUS_PUBLISHED,
				'h' => Page::STATUS_HIDDEN
			);

			if ( isset( $page_status[$query[1]] ) )
			{
				$childrens = Page::find( array( 'where' => 'page.status_id = ' . $page_status[$query[1]] ) );
			}
		}
		else if ( substr( $query, 0, 1 ) == '-' )
		{
			$query = trim( substr( $query, 1 ) );
			$childrens = Page::find( array( 'where' => 'page.parent_id = (SELECT p.id FROM ' . TABLE_PREFIX . 'page AS p WHERE p.slug = "' . $query . '" LIMIT 1)' ) );
		}
		else
		{
			$childrens = Page::findAllLike( $query );
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
		$page_part = new PagePart( array(
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