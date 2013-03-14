<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */
class Model_Page extends Record
{
    const TABLE_NAME = 'pages';
    
    const STATUS_DRAFT			= 1;
    const STATUS_REVIEWED		= 50;
    const STATUS_PUBLISHED		= 100;
    const STATUS_HIDDEN			= 101;

    const LOGIN_NOT_REQUIRED	= 0;
    const LOGIN_REQUIRED		= 1;
    const LOGIN_INHERIT			= 2;
	
	public function filters()
	{
		return array(
			'title' => array(
				array('integer')
			)
		);
	}

	public static function logins()
	{
		return array(
			self::LOGIN_NOT_REQUIRED => __('Not required'),
			self::LOGIN_REQUIRED => __('Required'),
			self::LOGIN_INHERIT => __('inherit')
		);
	}

	public static function statuses()
	{
		return array(
			self::STATUS_DRAFT => __('Draft'),
			self::STATUS_REVIEWED => __('Reviewed'),
			self::STATUS_PUBLISHED => __('Published'),
			self::STATUS_HIDDEN => __('Hidden')
		);
	}

	public function beforeInsert()
    {		
        $this->created_on = date('Y-m-d H:i:s');
        $this->created_by_id = AuthUser::getId();
        
        $this->updated_on = $this->created_on;
        $this->updated_by_id = $this->created_by_id;
        
        if ($this->status_id == Model_Page::STATUS_PUBLISHED)
		{
            $this->published_on = date('Y-m-d H:i:s');
		}
        
		if ($this->position == 0)
		{
			$last_position = DB::select(array(DB::expr('MAX(position)'), 'pos'))
				->from(self::TABLE_NAME)
				->where('parent_id', '=', $this->parent_id)
				->execute()
				->get('pos', 0);
			
			$this->position = ((int) $last_position) + 1;
		}
		
        return TRUE;
    }
	
	public function afterInsert()
	{
		$page = DB::select('id')
			->from(self::TABLE_NAME)
			->where('id', '<>', $this->id)
			->where('slug', '=', $this->slug)
			->where('parent_id', '=', $this->parent_id)
			->execute()
			->get('id');
		
		if ($page !== NULL)
		{
			DB::update(self::TABLE_NAME)
				->set(array(
					'slug' => DB::expr('CONCAT(slug, "-", id)')
				))
				->where('id', '=', $this->id)
				->where('parent_id', '=', $this->parent_id)
				->execute();
		}
		
		return TRUE;
	}
    
    public function beforeUpdate()
    {	
        if( empty($this->published_on) && $this->status_id == Model_Page::STATUS_PUBLISHED)
        {
            $this->published_on = date('Y-m-d H:i:s');
        }
        
        $this->updated_by_id = AuthUser::getId();
        $this->updated_on = date('Y-m-d H:i:s');
		
        return TRUE;
    }
	
	public function afterUpdate()
	{
		$cache = Cache::instance();
		$cache->delete_tag('pages');
		$cache->delete_tag('page_parts');
		$cache->delete_tag('page_tags');

		return $this->afterInsert();
	}
	
	public function beforeDelete()
	{
		// need to delete subpages
		self::deleteByParentId($this->id);
		
		$cache = Cache::instance();
		$cache->delete_tag('pages');
		$cache->delete_tag('page_parts');
		$cache->delete_tag('page_tags');
		return TRUE;
	}
	
	public function get_status()
	{
		switch ($this->status_id)
		{
			case Model_Page::STATUS_DRAFT: 
				return UI::label(__('Draft'), 'info');
			case Model_Page::STATUS_REVIEWED: 
				return UI::label(__('Reviewed'), 'info');
			case Model_Page::STATUS_HIDDEN:   
				return UI::label(__('Hidden'), 'default');
			case Model_Page::STATUS_PUBLISHED:
				if( strtotime($this->published_on) > time() )
					return UI::label(__('Pending'), 'success');
				else
					return UI::label(__('Published'), 'success');
		}
		
		return UI::label(__('None'), 'default');
	}
	
	public function get_public_anchor()
	{
		return HTML::anchor($this->get_frontend_url(), UI::label(UI::icon( 'globe icon-white' ) . ' ' . __('View page')), array(
			'class' => 'item-preview', 'target' => '_blank'
		));
	}

	public function get_uri()
    {
        $result = NULL;

        $parent = $this->findById($this->parent_id);
		
        if( $parent != NULL && $parent->slug != '' )
		{
            $result = $parent->get_uri().'/'.$this->slug;
        }
        else
		{
            $result = $this->slug;
        }

        return $result;
    }
	
	public function get_frontend_url()
	{
		return URL::frontend($this->get_uri(), TRUE);
	}
	
	public function get_url()
	{
		return URL::backend( 'page/edit/'.$this->id );
	}

	public function get_tags()
    {
		return Model_Page_Tag::find_by_page( $this->id );
    }
    
    public function save_tags($tags)
    {
        return Model_Page_Tag::save_by_page( $this->id, $tags );
    }
    
    public static function find($clause = array())
    {
        $tablename = self::tableName('Model_Page');
        $tablename_user = self::tableName('User');
		
		$sql = DB::select('page.*')
			->select(array('author.username', 'created_by_name'))
			->select(array('updator.username', 'updated_by_name'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array(User::tableName(), 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id');
        
        // Prepare SQL
        $sql = self::_conditions($sql, $clause);
		
		$query = $sql
			->as_object(__CLASS__)
			->execute();

        // Run!
        if (Arr::get($clause, 'limit') == 1)
        {
            return $query->current();
        }
        else
        {
            return $query->as_array('id');
        }
    }
    
    public static function findAll($clause = array())
    {
        return self::find($clause);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => array(array('page.id', '=', (int) $id)),
			'limit' => 1
        ));
    }
	
	public static function findAllLike($query)
	{
		$childrens = Record::findAllFrom(__CLASS__, array(
			'or_where' => array(
				array(DB::expr('LOWER(title)'), 'like', '%:query%'),
				array('slug', 'like', '%:query%'),
				array('breadcrumb', 'like', '%:query%'),
				array('keywords', 'like', '%:query%'),
				array('description', 'like', '%:query%'),
				array('published_on', 'like', '%:query%'),
				array('created_on', 'like', '%:query%'),
			)
		), 
		array(
			':query' => DB::expr($query)
		));
		
		return $childrens;
	}
    
    public static function childrenOf($id, $clause = array())
    {
		$default_clause = array(
			'where' => array(array('parent_id', '=', $id)),
			'order_by' => array(
				array('position', 'desc'),
				array('page.created_on', 'desc')
			));
		
		if( is_array( $clause ))
		{
			$default_clause = Arr::merge($default_clause, $clause);
		}

        return self::find($default_clause);
    }
    
    public static function hasChildren($id)
    {
        return (boolean) self::countFrom('Model_Page', array(
			'where' => array(array('parent_id', '=', (int) $id))));
    }
	
	public static function deleteByParentId( $parent_id )
	{
		$pages = self::findAllFrom('Model_Page', array(
			'where' => array(array('parent_id', '=', (int) $parent_id))));
		
		$result = TRUE;
		
		foreach ($pages as $page)
		{
			if ( !$page->delete())
			{
				$result = FALSE;
			}
		}
		
		return $result;
	}
	
	public function get_permissions()
	{
		if ( empty($this->id) )
		{
			return Model_Permission::get_all();
		}
			
		return Model_Page_Permission::find_by_page( $this->id );
	}

	public function save_permissions( $permissions )
	{
		return Model_Page_Permission::save_by_page($this->id, $permissions);
	}
    
} // end Model_Page class